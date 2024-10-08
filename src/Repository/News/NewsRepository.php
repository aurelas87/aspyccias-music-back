<?php

namespace App\Repository\News;

use App\Entity\News\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    private function createFindNewsByLocaleQueryBuilder(
        string $locale,
        string $sortField,
        string $sortOrder
    ): QueryBuilder {

        $qb = $this->createQueryBuilder('n');

        $qb->addSelect('t')
            ->innerJoin('n.translations', 't')
            ->where($qb->expr()->eq('t.locale', ':locale'))
            ->orderBy("n.$sortField", $sortOrder)
            ->setParameter('locale', $locale);

        return $qb;
    }

    private function countTotal(): int
    {
        return $this
            ->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findPaginatedLocalized(string $locale, int $offset, int $limit, string $sortField, string $sortOrder): array
    {
        $qb = $this->createFindNewsByLocaleQueryBuilder($locale, $sortField, $sortOrder);

        return [
            'items' => $qb->getQuery()->setFirstResult($offset)->setMaxResults($limit)->getResult(),
            'total' => $this->countTotal(),
        ];
    }

    /**
     * @return News[]
     */
    public function findLatestLocalized(string $locale, int $limit, string $sortField, string $sortOrder): array
    {
        $qb = $this->createFindNewsByLocaleQueryBuilder($locale, $sortField, $sortOrder);

        return $qb->getQuery()->setMaxResults($limit)->getResult();
    }

    public function findOneBySlugLocalized(string $slug, string $locale): ?News
    {
        $qb = $this->createQueryBuilder('n');
        $qb->addSelect('t')
            ->innerJoin('n.translations', 't')
            ->where($qb->expr()->eq('n.slug', ':slug'))
            ->andWhere($qb->expr()->eq('t.locale', ':locale'))
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
