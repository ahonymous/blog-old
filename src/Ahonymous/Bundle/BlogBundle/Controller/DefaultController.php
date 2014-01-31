<?php

namespace Ahonymous\Bundle\BlogBundle\Controller;

use Ahonymous\Bundle\BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Yaml\Yaml;

class DefaultController extends Controller
{
    /**
     * @Template()
     * @param  int   $page
     * @return array
     */
    public function indexAction($page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem(
            $this->get('translator')->trans('menu.home'),
            $this->get("router")->generate("home")
        );
//        var_dump($this->get('data_collector.router')->getTargetUrl());
//        var_dump($this->getCloud());

        $query = $em->getRepository('AhonymousBlogBundle:Article')
            ->findAllArticles();

        $adapter = new DoctrineORMAdapter($query);
        $toRender = $this->getPagerfanta($adapter, $page, 2);

        return array('articles' => $toRender, 'title' => 'Home');
    }

    /**
     * @Template()
     */
    public function aboutAction()
    {
        $about = Yaml::parse(__DIR__ . '/../DataFixtures/ORM/Data/about.yml');
        $articleObject = new Article();
        $articleObject
            ->setName($about['about']['name'])
            ->setBody($about['about']['body'])
        ;
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem(
            $this->get('translator')->trans('menu.about'),
            $this->get("router")->generate("about")
        );

        return array('article'=>$articleObject);
    }

    /**
     * @Template("AhonymousBlogBundle::sidebar.html.twig")
     */
    public function sidebarMostViewedArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $queryMostViewed = $em->getRepository('AhonymousBlogBundle:Article')
            ->findMostViewedName(2);

        return array(
            'articles' => $queryMostViewed,
            'name' => $this->get('translator')->trans('sidebar.mVArticles'),
            'path_route' => 'article_show'
        );
    }

    /**
     * @Template("AhonymousBlogBundle::sidebar.html.twig")
     */
    public function sidebarLastArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $queryLastArticles = $em->getRepository('AhonymousBlogBundle:Article')
            ->findLastName(2);

        return array(
            'articles' => $queryLastArticles,
            'name' => $this->get('translator')->trans('sidebar.lArticles'),
            'path_route' => 'article_show'
        );
    }

    /**
     * @Template("AhonymousBlogBundle::sidebar.html.twig")
     */
    public function sidebarLastGuestsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sidebarQuery = $em->getRepository("AhonymousGuestBundle:Guest")
            ->toSidebarGuest(2);

        return array(
            'articles' => $sidebarQuery,
            'name' => $this->get('translator')->trans('sidebar.lGuest'),
            'path_route' => '_single'
        );
    }

    /**
     * @param  Request                                            $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function searchAction(Request $request)
    {
        $mySearchRequest = $request->createFromGlobals();
        $searchString = trim($mySearchRequest->request->get('search'));

        if (strlen($searchString) == 0) {
            return $this->redirect($this->generateUrl('home'));
        } else {
            return $this->redirect($this->generateUrl(
                    '_find',
                    array('searched' => $searchString)
                )
            );
        }
    }

    /**
     * @param $searched
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function findAction($searched, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $searchArray = array_map('trim', explode(' ',$searched));
        $searchArray = array_map('strtolower', $searchArray);
        $searchArray = array_unique($searchArray);

        $query = $em->getRepository('AhonymousBlogBundle:Article')
            ->search($searchArray);

        $adapter = new DoctrineORMAdapter($query);
        $toRender = $this->getPagerfanta($adapter, $page, 3);

        return $this->render(
            'AhonymousBlogBundle:Default:index.html.twig',
            array(
                'articles' => $toRender,
                'title' => 'Searched ',
                'searcher' => $searched
            )
        );
    }

    /**
     * @param  DoctrineORMAdapter                                            $adapter
     * @param  int                                                           $page
     * @param  int                                                           $maxPerPage
     * @return Pagerfanta
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getPagerfanta($adapter, $page = 1, $maxPerPage = 2)
    {
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($maxPerPage);

        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException('Illegal page');
        }

        return $pager;
    }

    /**
     * @Template("AhonymousBlogBundle::cloud.html.twig")
     */
    public function getCloudAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AhonymousBlogBundle:Category')
            ->tags();
        $toRender = $query->getResult();

        $sum = $em->getRepository('AhonymousBlogBundle:Category')
            ->sum();
        shuffle($toRender);

        return array(
                'tags' => $toRender,
                'title' => $this->get('translator')->trans('sidebar.cloud'),
                'sum' => $sum[0][1]
        );
    }
}
