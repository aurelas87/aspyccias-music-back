<?php

namespace App\Repository;

use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Profile>
 */
class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    public function findProfileByName(string $name): ?Profile
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('p.name', ':name'))
            ->setParameter('name', $name);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
