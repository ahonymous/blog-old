<?php

namespace Ahonymous\Bundle\GuestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'Alex');
    }
}
