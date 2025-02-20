<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findLatest(int $limit = 6): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.publishDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
} 