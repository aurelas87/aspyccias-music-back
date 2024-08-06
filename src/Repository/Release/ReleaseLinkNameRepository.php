<?php

namespace App\Repository\Release;

use App\Entity\Release\ReleaseCreditType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReleaseCreditType>
 */
class ReleaseLinkNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReleaseCreditType::class);
    }
}
