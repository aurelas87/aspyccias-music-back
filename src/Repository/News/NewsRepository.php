<?php

namespace App\Repository\News;

use App\Entity\News\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    private function countTotal(): int
    {
        return $this
            ->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findPaginated(string $locale, int $offset, int $limit, string $sortField, string $sortOrder): array
    {
        $qb = $this->createQueryBuilder('n');

        $qb->addSelect('t')
            ->innerJoin('n.translations', 't')
            ->where($qb->expr()->eq('t.locale', ':locale'))
            ->orderBy("n.$sortField", $sortOrder)
            ->setParameter('locale', $locale);

        return [
            'items' => $qb->getQuery()->setFirstResult($offset)->setMaxResults($limit)->getResult(),
            'total' => $this->countTotal(),
        ];
    }
}
