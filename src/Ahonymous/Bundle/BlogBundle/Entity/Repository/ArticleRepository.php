<?php

namespace Ahonymous\Bundle\BlogBundle\Entity\Repository;

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
}
