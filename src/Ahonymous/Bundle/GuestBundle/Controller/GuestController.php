<?php

namespace Ahonymous\Bundle\GuestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ahonymous\Bundle\GuestBundle\Entity\Guest;
use Ahonymous\Bundle\GuestBundle\Form\GuestType;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class GuestController extends Controller
{
    /**
     * @Template()
     */
    public function viewAction($page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AhonymousGuestBundle:Guest')->findDESCGuests();
        $adapter = new DoctrineORMAdapter($query);

        if (!$page) {
            $page = 1;
        }
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(5);
//        $pager->getCurrentPage($page);

        try {
            $pager->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException('Illegal page');
        }
        $entity = new Guest(new \DateTime("now"));
        $entity->setEditedTime(new \DateTime("now"));

        $form = $this->createForm(new GuestType(), $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('guest'));
        }

        return array(
            'guests' => $pager,
            'form' => $form->createView()
        );
    }

    /**
     * @Template()
     */
    public function singleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $guest = $em->getRepository('AhonymousGuestBundle:Guest')->findById($id);

        return array(
            'guest' => $guest[0]
        );
    }

    /**
     * @Template()
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $guest = $em->getRepository('AhonymousGuestBundle:Guest')->find($id);

        if (!$guest) {
            throw $this->createNotFoundException('Unable to find Guest entity.');
        }

        $em->remove($guest);
        $em->flush();

        return $this->redirect($this->generateUrl('guest'));
    }
}
