<?php

namespace Ahonymous\Bundle\GuestBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GuestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GuestRepository extends EntityRepository
{
    public function findDESCGuests($limit = null)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT g FROM AhonymousGuestBundle:Guest g ORDER BY g.id DESC')
            ->setMaxResults($limit);
    }

    public function toSidebarGuest($limit = null)
    {
        return $this->findDESCGuests($limit)->getResult();
    }
}
