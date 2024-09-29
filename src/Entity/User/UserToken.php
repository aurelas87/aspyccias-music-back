<?php

namespace App\Entity\User;

use App\Repository\User\UserTokenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: UserTokenRepository::class)]
class UserToken
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'token')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accessToken = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $accessTokenExpirationDate = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getAccessTokenExpirationDate(): ?\DateTimeInterface
    {
        return $this->accessTokenExpirationDate;
    }

    public function setAccessTokenExpirationDate(?\DateTimeInterface $accessTokenExpirationDate): static
    {
        $this->accessTokenExpirationDate = $accessTokenExpirationDate;

        return $this;
    }
}
