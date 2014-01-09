<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.12.13
 * Time: 23:29
 */

namespace Ahonymous\Bundle\GuestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ahonymous\Bundle\GuestBundle\Entity\Guest;

class LoadGuestData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<10;$i++) {
            $guestObject = new Guest(new \DateTime("now"));

            $guestObject->setName('guest'.$i);
            $guestObject->setEmail('guest'.$i.'@at.ta');
            $guestObject->setMessage('It\'s message #'.$i.'.');
            $guestObject->setEditedTime(new \DateTime("now"));

            $manager->persist($guestObject);
        }
        $manager->flush();
    }
}
