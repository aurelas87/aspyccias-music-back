<?php

namespace App\Service\Release;

use App\Entity\Release\ReleaseCreditType;
use App\Entity\Release\ReleaseCreditTypeTranslation;
use App\Exception\Release\ReleaseCreditTypeInUseException;
use App\Model\Release\ReleaseCreditTypeDTO;
use App\Repository\Release\ReleaseCreditRepository;
use App\Repository\Release\ReleaseCreditTypeRepository;
use App\Service\EntitySanitizer;
use Doctrine\ORM\EntityManagerInterface;

class ReleaseCreditTypeService
{
    private ReleaseCreditTypeRepository $releaseCreditTypeRepository;
    private ReleaseCreditRepository $releaseCreditRepository;
    private EntitySanitizer $entitySanitizer;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ReleaseCreditTypeRepository $releaseCreditTypeRepository,
        ReleaseCreditRepository $releaseCreditRepository,
        EntitySanitizer $entitySanitizer,
        EntityManagerInterface $entityManager
    ) {
        $this->releaseCreditTypeRepository = $releaseCreditTypeRepository;
        $this->releaseCreditRepository = $releaseCreditRepository;
        $this->entitySanitizer = $entitySanitizer;
        $this->entityManager = $entityManager;
    }

    /**
     * @return ReleaseCreditType[]
     */
    public function listReleaseCreditTypesForAdmin(): array
    {
        return $this->releaseCreditTypeRepository->findBy([], ['creditNameKey' => 'ASC']);
    }

    public function addReleaseCreditType(ReleaseCreditTypeDTO $releaseCreditTypeDTO): void
    {
        $releaseCreditType = new ReleaseCreditType();
        $releaseCreditType->setCreditNameKey($releaseCreditTypeDTO->creditNameKey);
        $releaseCreditType->addTranslation(
            new ReleaseCreditTypeTranslation()
                ->setLocale('fr')
                ->setCreditName($releaseCreditTypeDTO->creditNameFr)
        );
        $releaseCreditType->addTranslation(
            new ReleaseCreditTypeTranslation()
                ->setLocale('en')
                ->setCreditName($releaseCreditTypeDTO->creditNameEn)
        );

        $this->entitySanitizer->sanitizeEntity($releaseCreditType);

        $this->entityManager->persist($releaseCreditType);
        $this->entityManager->flush();
    }

    public function editReleaseCreditType(ReleaseCreditType $releaseCreditType, ReleaseCreditTypeDTO $releaseCreditTypeDTO): void
    {
        $releaseCreditType->setCreditNameKey($releaseCreditTypeDTO->creditNameKey);

        foreach ($releaseCreditType->getTranslations() as $releaseCreditTypeTranslation) {
            if ($releaseCreditTypeTranslation->getLocale() === 'fr') {
                $releaseCreditTypeTranslation->setCreditName($releaseCreditTypeDTO->creditNameFr);
            } else {
                $releaseCreditTypeTranslation->setCreditName($releaseCreditTypeDTO->creditNameEn);
            }
        }

        $this->entitySanitizer->sanitizeEntity($releaseCreditType);

        $this->entityManager->flush();
    }

    public function deleteReleaseCreditType(ReleaseCreditType $releaseCreditType): void
    {
        if ($this->releaseCreditRepository->isReleaseCreditTypeInUse($releaseCreditType)) {
            throw new ReleaseCreditTypeInUseException();
        }

        $this->entityManager->remove($releaseCreditType);
        $this->entityManager->flush();
    }
}
