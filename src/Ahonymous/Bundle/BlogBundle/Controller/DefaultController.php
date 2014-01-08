<?php

namespace Ahonymous\Bundle\BlogBundle\Controller;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AhonymousBlogBundle:Article')->findAllArticles();
        $queryLastName = $em->getRepository('AhonymousBlogBundle:Article')->findLastName(2);
        $sidebar_last = $queryLastName->getResult();

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

        return array(
            'articles' => $pager,
            'sidebar_last' => $sidebar_last
        );
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
