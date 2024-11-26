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
            ->orderBy($qb->expr()->desc('r.releaseDate'))
            ->setParameter('releaseType', $releaseType)
            ->setParameter('locale', $locale);

        return $qb->getQuery()->getResult();
    }

    public function findOneBySlugLocalized(string $slug, string $locale): ?Release
    {
        $qb = $this->createQueryBuilder('r');

        $qb->addSelect('t', 'c', 'ct', 'ctt')
            ->innerJoin('r.translations', 't')
            ->leftJoin('r.credits', 'c')
            ->leftJoin('c.releaseCreditType', 'ct')
            ->leftJoin('ct.translations', 'ctt')
            ->where($qb->expr()->eq('r.slug', ':slug'))
            ->andWhere($qb->expr()->eq('t.locale', ':locale'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('ctt.locale', ':locale'),
                $qb->expr()->isNull('ctt.locale')
            ))
            ->orderBy('ct.creditNameKey', 'ASC')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findPaginated(
        int $offset,
        int $limit,
        string $sortField,
        string $sortOrder,
    ): array {
        $qb = $this->createQueryBuilder('r');
        $qb->orderBy("r.$sortField", $sortOrder);

        return [
            'items' => $qb->getQuery()->setFirstResult($offset)->setMaxResults($limit)->getResult(),
            'total' => $this->createQueryBuilder('r')->select('COUNT(r)')->getQuery()->getSingleScalarResult(),
        ];
    }
}
