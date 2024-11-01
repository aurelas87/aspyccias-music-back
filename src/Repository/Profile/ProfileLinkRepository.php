<?php

namespace App\Repository\Profile;

use App\Entity\Profile\ProfileLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfileLink>
 */
class ProfileLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfileLink::class);
    }

    public function findMaxPosition(): int
    {
        $qb = $this->createQueryBuilder('pl');
        $qb->select($qb->expr()->max('pl.position'));

        return $qb->getQuery()->getSingleScalarResult();
    }
}
