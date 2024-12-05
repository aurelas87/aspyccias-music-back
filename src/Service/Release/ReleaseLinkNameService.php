<?php

namespace App\Service\Release;

use App\Entity\Release\ReleaseLinkName;
use App\Exception\Release\ReleaseLinkNameInUseException;
use App\Model\Release\ReleaseLinkNameDTO;
use App\Repository\Release\ReleaseLinkNameRepository;
use App\Repository\Release\ReleaseLinkRepository;
use App\Service\EntitySanitizer;
use Doctrine\ORM\EntityManagerInterface;

class ReleaseLinkNameService
{
    private ReleaseLinkNameRepository $releaseLinkNameRepository;
    private ReleaseLinkRepository $releaseLinkRepository;
    private EntitySanitizer $entitySanitizer;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ReleaseLinkNameRepository $releaseLinkNameRepository,
        ReleaseLinkRepository $releaseLinkRepository,
        EntitySanitizer $entitySanitizer,
        EntityManagerInterface $entityManager
    ) {
        $this->releaseLinkNameRepository = $releaseLinkNameRepository;
        $this->releaseLinkRepository = $releaseLinkRepository;
        $this->entitySanitizer = $entitySanitizer;
        $this->entityManager = $entityManager;
    }

    /**
     * @return ReleaseLinkName[]
     */
    public function listReleaseLinkNamesForAdmin(): array
    {
        return $this->releaseLinkNameRepository->findBy([], ['linkName' => 'ASC']);
    }

    public function addReleaseLinkName(ReleaseLinkNameDTO $releaseLinkNameDTO): void
    {
        $releaseLinkName = new ReleaseLinkName();
        $releaseLinkName->setLinkName($releaseLinkNameDTO->linkName);

        $this->entitySanitizer->sanitizeEntity($releaseLinkName);

        $this->entityManager->persist($releaseLinkName);
        $this->entityManager->flush();
    }

    public function editReleaseLinkName(ReleaseLinkName $releaseLinkName, ReleaseLinkNameDTO $releaseLinkNameDTO): void
    {
        $releaseLinkName->setLinkName($releaseLinkNameDTO->linkName);

        $this->entitySanitizer->sanitizeEntity($releaseLinkName);

        $this->entityManager->flush();
    }

    public function deleteReleaseCreditType(ReleaseLinkName $releaseLinkName): void
    {
        if ($this->releaseLinkRepository->isReleaseLinkInUse($releaseLinkName)) {
            throw new ReleaseLinkNameInUseException();
        }

        $this->entityManager->remove($releaseLinkName);
        $this->entityManager->flush();
    }
}
