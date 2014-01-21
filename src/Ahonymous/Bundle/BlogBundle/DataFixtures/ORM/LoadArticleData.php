<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 08.01.14
 * Time: 17:46
 */

namespace Ahonymous\Bundle\BlogBundle\DataFixtures;

use Ahonymous\Bundle\BlogBundle\Entity\Article;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Common\Collections\ArrayCollection;

class LoadArticleData extends AbstractFixture implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $articles = Yaml::parse($this->getYmlFile());

        foreach ($articles['articles'] as $article) {
            $articleObject = new Article();

            $articleObject->setName($article['name']);
            $articleObject->setBody($article['body']);
            $articleObject->setAuthor($article['author']);

            foreach ($article['categories'] as $reference) {
                $articleObject->addCategory($this->getReference($reference));
            }

            $manager->persist($articleObject);
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
        return 2;
    }

    protected function getYmlFile()
    {
        return __DIR__ . '/Data/article.yml';
    }

    protected function getReferencesFromArray(array $array)
    {
        $outputReferences = new ArrayCollection();

        foreach ($array as $reference) {
            $outputReferences->add($this->getReference($reference));
        }

        return $outputReferences;
    }
}
