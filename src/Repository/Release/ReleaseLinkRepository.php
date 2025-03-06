<?php

namespace App\Repository\Release;

use App\Entity\Release\ReleaseLink;
use App\Entity\Release\ReleaseLinkName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReleaseLink>
 */
class ReleaseLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReleaseLink::class);
    }

    public function isReleaseLinkInUse(ReleaseLinkName $releaseLinkName): bool
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('count(l.category)')
            ->where($qb->expr()->eq('l.releaseLinkName', ':releaseLinkName'))
            ->setParameter('releaseLinkName', $releaseLinkName);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }
}
