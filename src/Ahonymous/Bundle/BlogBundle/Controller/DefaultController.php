<?php

namespace Ahonymous\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'Alex');
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        return array('name' => 'About');
    }

    /**
     * @Route("/guest", name="guest")
     * @Template()
     */
    public function guestAction()
    {
        return array('name' => 'guest');
    }
}
