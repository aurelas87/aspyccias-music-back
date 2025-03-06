<?php

namespace App\Repository\Release;

use App\Entity\Release\ReleaseCredit;
use App\Entity\Release\ReleaseCreditType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReleaseCredit>
 */
class ReleaseCreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReleaseCredit::class);
    }

    public function isReleaseCreditTypeInUse(ReleaseCreditType $releaseCreditType): bool
    {
        $qb = $this->createQueryBuilder('rc');
        $qb->select('count(rc.fullName)')
            ->where($qb->expr()->eq('rc.releaseCreditType', ':releaseCreditType'))
            ->setParameter('releaseCreditType', $releaseCreditType);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }
}
