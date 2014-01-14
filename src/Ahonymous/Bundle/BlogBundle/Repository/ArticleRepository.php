<?php

namespace Ahonymous\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function findAllArticles()
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM AhonymousBlogBundle:Article a ORDER BY a.created DESC');

        return $query;
    }

    public function findLastName($limit = 5)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a.name, a.slug FROM AhonymousBlogBundle:Article a ORDER BY a.created DESC')
            ->setMaxResults($limit)
        ;

        return $query->getResult();
    }

    public function findMostViewedName($limit = 5)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT a.name, a.slug FROM AhonymousBlogBundle:Article a ORDER BY a.viewed DESC')
            ->setMaxResults($limit)
        ;

        return $query->getResult();
    }

    public function search($search)
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('a')
            ->from('AhonymousBlogBundle:Article', 'a');

        foreach ($search as $itemValue) {
            $query->orWhere('a.name LIKE \'%'.$itemValue.'%\'')
                ->orWhere('a.body LIKE \'%'.$itemValue.'%\'');
        }

        return $query->getQuery();
    }
}
