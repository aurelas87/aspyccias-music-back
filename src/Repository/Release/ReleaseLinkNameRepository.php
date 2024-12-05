<?php

namespace App\Repository\Release;

use App\Entity\Release\ReleaseLinkName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReleaseLinkName>
 */
class ReleaseLinkNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReleaseLinkName::class);
    }
}
