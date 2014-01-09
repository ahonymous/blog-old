<?php

namespace Ahonymous\Bundle\BlogBundle\Controller;

use Ahonymous\Bundle\BlogBundle\Entity\Article;
use Ahonymous\Bundle\GuestBundle\Entity\Guest;
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
     */
    public function indexAction($page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AhonymousBlogBundle:Article')->findAllArticles();

        $adapter = new DoctrineORMAdapter($query);

        return array('articles' => $this->getPagerfanta($adapter, $page));
    }

    /**
     * @Template()
     */
    public function aboutAction()
    {
        $about = Yaml::parse(__DIR__ . '/../DataFixtures/ORM/Data/aboute.yml');
        $articleObject = new Article();
        $articleObject
            ->setName($about['about']['name'])
            ->setBody($about['about']['body'])
        ;

        return array('article'=>$articleObject);
    }

    /**
     * @Template()
     */
    public function guestAction()
    {
        return array();
    }

    /**
     * @Template("AhonymousBlogBundle::sidebar.html.twig")
     */
    public function sidebarMostViewedArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $queryMostViewed = $em->getRepository('AhonymousBlogBundle:Article')->findMostViewedName(2);

        return array(
            'articles' => $queryMostViewed->getResult(),
            'name' => 'Most Viewed Articles',
            'path_route' => 'article_show'
        );
    }

    /**
     * @Template("AhonymousBlogBundle::sidebar.html.twig")
     */
    public function sidebarLastArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $queryLastName = $em->getRepository('AhonymousBlogBundle:Article')->findLastName(2);
        $sidebar_last = $queryLastName->getResult();

        return array('articles' => $sidebar_last, 'name' => 'Last Articles', 'path_route' => 'article_show');
    }

    /**
     * @Template("AhonymousBlogBundle::sidebar.html.twig")
     */
    public function sidebarLastGuestsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sidebarQuery = $em->getRepository("AhonymousGuestBundle:Guest")->findDESCGuests(2);
        $sidebar = $sidebarQuery->getResult();

        return array('articles' => $sidebar, 'name' => 'Last Guests', 'path_route' => '_single');
    }

    public function searchAction(Request $request, $page)
    {
        $mySearchRequest = $request->createFromGlobals();
        $searchString = trim($mySearchRequest->request->get('search'));
        $em = $this->getDoctrine()->getManager();

        if (strlen($searchString) == 0) {
            return $this->redirect($this->generateUrl('home'));
        }

        $searchArray = array_map('trim', explode(' ',$searchString));
        $searchArray = array_map('strtolower', $searchArray);
        $searchArray = array_unique($searchArray);

        var_dump($searchArray);

        foreach ($searchArray as $searchWord) {
            var_dump($searchWord);
            $s = $em->getRepository('AhonymousBlogBundle:Article')->search($searchWord);
            $searchedQuery[]= $s->getResult();
        }
        var_dump($searchedQuery);
//        var_dump(array_map( 'unserialize', array_unique( array_map( 'serialize', $searchedQuery ) ) ));

//        return $this->render('AhonymousBlogBundle:Default:index.html.twig', array('articles' => $searchedQuery));
    }

    protected function getPagerfanta($adapter, $page = 1)
    {
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(2);

        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException('Illegal page');
        }

        return $pager;
    }
}
