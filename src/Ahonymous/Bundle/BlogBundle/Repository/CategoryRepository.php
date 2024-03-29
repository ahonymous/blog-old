<?php

namespace Ahonymous\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function tags()
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('c')
            ->from('AhonymousBlogBundle:Category', 'c')
            ->orderBy('c.countArticles', 'DESC')
            ->setMaxResults(10)
        ;

        return $query->getQuery();
    }

    public function sum()
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT sum(c.countArticles) FROM AhonymousBlogBundle:Category c')
        ;

        return $query->getResult();
    }

    public function howArticle($slug)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT count(c.articles) FROM AhonymousBlogBundle:Category c where c.slug = :1 ')
            ->setParameter(1, "'".$slug."'")
        ;

        return $query->getResult();
    }
}
