<?php

namespace Ahonymous\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ahonymous\Bundle\BlogBundle\Entity\Article;
use Ahonymous\Bundle\BlogBundle\Form\ArticleType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AhonymousBlogBundle:Article')->findAllArticles();
        $adapter = new DoctrineORMAdapter($query);

        if (!$page) {
            $page = 1;
        }
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(2);

        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException('Illegal page');
        }


        return array('articles' => $pager);
    }

    /**
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function guestAction()
    {
        return array();
    }
}
