<?php

namespace App\Repository\Release;

use App\Entity\Release\Release;
use App\Model\Release\ReleaseType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Release>
 */
class ReleaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Release::class);
    }

    /**
     * @return Release[]
     */
    public function findByTypeLocalized(ReleaseType $releaseType, string $locale): array
    {
        $qb = $this->createQueryBuilder('r');

        $qb->addSelect('t')
            ->innerJoin('r.translations', 't')
            ->where($qb->expr()->eq('r.type', ':releaseType'))
            ->andWhere($qb->expr()->eq('t.locale', ':locale'))
            ->orderBy($qb->expr()->desc('r.release_date'))
            ->setParameter('releaseType', $releaseType)
            ->setParameter('locale', $locale);

        return $qb->getQuery()->getResult();
    }

    public function findOneBySlugLocalized(string $slug, string $locale): ?Release
    {
        $qb = $this->createQueryBuilder('r');

        $qb->addSelect('t')
            ->innerJoin('r.translations', 't')
            ->where($qb->expr()->eq('r.slug', ':slug'))
            ->andWhere($qb->expr()->eq('t.locale', ':locale'))
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
