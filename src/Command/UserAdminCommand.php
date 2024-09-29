<?php

namespace App\Command;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'user:admin',
    description: 'Register or modify the admin user with the default email address',
)]
class UserAdminCommand extends Command
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $manager;
    private string $aspycciasEmail;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $manager,
        string $aspycciasEmail
    ) {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
        $this->aspycciasEmail = $aspycciasEmail;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('password', InputArgument::REQUIRED, 'The new password to encode for this user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $password = $input->getArgument('password');

            $user = $this->userRepository->findOneBy(['email' => $this->aspycciasEmail]);
            if ($user) {
                $io->note("Found an existing user with email $this->aspycciasEmail");
                $doPersist = false;
            } else {
                $io->note("New user with email $this->aspycciasEmail will be created");

                $user = new User();
                $user->setEmail($this->aspycciasEmail);

                $doPersist = true;
            }

            $user->setRoles(['ROLE_ADMIN']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            if ($doPersist) {
                $this->manager->persist($user);
            }

            $this->manager->flush();

            $io->success("User with email $this->aspycciasEmail has been saved!");

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $io->error('Command failed with error: '.$exception->getMessage());

            return Command::FAILURE;
        }
    }

    public function isEnabled(): bool
    {
        return getenv('APP_ENV') === 'dev';
    }
}
