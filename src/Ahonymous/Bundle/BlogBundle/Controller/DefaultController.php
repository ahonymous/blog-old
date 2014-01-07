<?php

namespace Ahonymous\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ahonymous\Bundle\BlogBundle\Entity\Article;
use Ahonymous\Bundle\BlogBundle\Form\ArticleType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AhonymousBlogBundle:Article')->findAllArticles();

        return array('articles' => $articles);
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Route("/guest", name="guest")
     * @Template()
     */
    public function guestAction()
    {
        return array();
    }
}
