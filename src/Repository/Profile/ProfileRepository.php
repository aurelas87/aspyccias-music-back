<?php

namespace App\Repository\Profile;

use App\Entity\Profile\Profile;
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

    public function findProfileByLocale(string $locale): ?Profile
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('p.locale', ':locale'))
            ->setParameter('locale', $locale);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
