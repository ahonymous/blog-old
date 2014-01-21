<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 08.01.14
 * Time: 17:25
 */

namespace Ahonymous\Bundle\BlogBundle\DataFixtures\ORM;

use Ahonymous\Bundle\BlogBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoryData extends AbstractFixture implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getCategoryArray() as $category) {
            $categoryObject = new Category();

            $categoryObject->setName($category);
            $manager->persist($categoryObject);
            $this->addReference($category, $categoryObject);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

    protected function getCategoryArray()
    {
        return array(
            "ubuntu",
            "linux",
            "php",
            "javascript",
            "apache",
            "ajax",
            "nginx",
            "symfony",
            "yii",
        );
    }
}
