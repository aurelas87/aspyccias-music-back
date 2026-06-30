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

    private function addLocalizationToQuery(QueryBuilder $qb, ?string $locale = null): QueryBuilder
    {
        if (is_null($locale)) {
            return $qb;
        }

        $qb->addSelect('t')
            ->innerJoin('n.translations', 't')
            ->andWhere($qb->expr()->eq('t.locale', ':locale'))
            ->setParameter('locale', $locale);

        return $qb;
    }

    private function createFindNewsLocalizedQueryBuilder(
        string $sortField,
        string $sortOrder,
        ?string $locale = null
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('n');
        $qb->orderBy("n.$sortField", $sortOrder);

        return $this->addLocalizationToQuery($qb, $locale);
    }

    private function countTotal(): int
    {
        return $this
            ->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findPaginatedLocalized(
        int $offset,
        int $limit,
        string $sortField,
        string $sortOrder,
        ?string $locale = null
    ): array {
        $qb = $this->createFindNewsLocalizedQueryBuilder($sortField, $sortOrder, $locale);

        return [
            'items' => $qb->getQuery()->setFirstResult($offset)->setMaxResults($limit)->getResult(),
            'total' => $this->countTotal(),
        ];
    }

    /**
     * @return News[]
     */
    public function findLatestLocalized(int $limit, string $sortField, string $sortOrder, string $locale): array
    {
        $qb = $this->createFindNewsLocalizedQueryBuilder($sortField, $sortOrder, $locale);

        return $qb->getQuery()->setMaxResults($limit)->getResult();
    }

    public function findOneBySlugLocalized(string $slug, ?string $locale = null): ?News
    {
        $qb = $this->createQueryBuilder('n');
        $qb->where($qb->expr()->eq('n.slug', ':slug'))
            ->setParameter('slug', $slug);

        $qb = $this->addLocalizationToQuery($qb, $locale);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
