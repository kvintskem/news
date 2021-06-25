<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getArticle($filter)
    {
        //@todo переписать на queryBuilder
        $rsm = new ResultSetMapping();
        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT a.* FROM article a
join article_tags at on a.id = at.article_id
WHERE at.tags_id IN (:ids)', $rsm
        )
            ->setParameter('ids', $filter);


            return $query->getArrayResult();
    }
}
