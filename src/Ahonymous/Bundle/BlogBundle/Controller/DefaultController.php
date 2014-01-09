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
     * @Template()
     */
    public function sidebarMostViewedArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $queryMostViewed = $em->getRepository('AhonymousBlogBundle:Article')->findMostViewedName(2);

        return array('sidebar_most_viewed' => $queryMostViewed->getResult());
    }

    /**
     * @Template()
     */
    public function sidebarLastArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $queryLastName = $em->getRepository('AhonymousBlogBundle:Article')->findLastName(2);
        $sidebar_last = $queryLastName->getResult();

        return array('sidebar_last' => $sidebar_last);
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
