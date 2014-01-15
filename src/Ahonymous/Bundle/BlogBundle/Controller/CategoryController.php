<?php

namespace Ahonymous\Bundle\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ahonymous\Bundle\BlogBundle\Entity\Category;
use Ahonymous\Bundle\BlogBundle\Form\CategoryType;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AhonymousBlogBundle:Category')
            ->findAll();

        return $this->render(
            'AhonymousBlogBundle:Category:index.html.twig',
            array(
             'categories' => $categories,
            )
        );
    }
    /**
     * Creates a new Category entity.
     *
     */
    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createCreateForm($category);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl(
                    'category_show',
                    array('slug' => $category->getSlug()))
            );
        }

        return $this->render(
            'AhonymousBlogBundle:Category:new.html.twig',
            array(
                'form'   => $form->createView(),
            )
        );
    }

    /**
    * Creates a form to create a Category entity.
    *
    * @param Category $category The category
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Category $category)
    {
        $form = $this->createForm(new CategoryType(), $category, array(
            'action' => $this->generateUrl('category_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     */
    public function newAction()
    {
        $category = new Category();
        $form   = $this->createCreateForm($category);

        return $this->render(
            'AhonymousBlogBundle:Category:new.html.twig',
            array(
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Category entity.
     *
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AhonymousBlogBundle:Category')
            ->findOneBy(array('slug' => $slug));

        if (!$category) {
            throw $this->createNotFoundException(
                'Unable to find Category entity.'
            );
        }

        $deleteForm = $this->createDeleteForm($slug);

        return $this->render(
            'AhonymousBlogBundle:Category:show.html.twig',
            array(
                'category'      => $category,
                'delete_form' => $deleteForm->createView()
            )
        );
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     */
    public function editAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AhonymousBlogBundle:Category')
            ->findOneBy(array('slug' => $slug));

        if (!$category) {
            throw $this->createNotFoundException(
                'Unable to find Category entity.'
            );
        }

        $editForm = $this->createEditForm($category);
        $deleteForm = $this->createDeleteForm($slug);

        return $this->render(
            'AhonymousBlogBundle:Category:edit.html.twig',
            array(
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
    * Creates a form to edit a Category entity.
    *
    * @param Category $category The category
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Category $category)
    {
        $form = $this->createForm(new CategoryType(), $category, array(
            'action' => $this->generateUrl(
                'category_update',
                array('slug' => $category->getSlug())
            ),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Category entity.
     *
     */
    public function updateAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AhonymousBlogBundle:Category')
            ->findOneBy(array('slug' => $slug));

        if (!$category) {
            throw $this->createNotFoundException(
                'Unable to find Category entity.'
            );
        }

        $deleteForm = $this->createDeleteForm($slug);
        $editForm = $this->createEditForm($category);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl(
                    'category_edit',
                    array('slug' => $slug))
            );
        }

        return $this->render(
            'AhonymousBlogBundle:Category:edit.html.twig',
            array(
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }
    /**
     * Deletes a Category entity.
     *
     */
    public function deleteAction(Request $request, $slug)
    {
        $form = $this->createDeleteForm($slug);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('AhonymousBlogBundle:Category')
                ->findOneBy(array('slug' => $slug));

            if (!$category) {
                throw $this->createNotFoundException(
                    'Unable to find Category entity.'
                );
            }

            $em->remove($category);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('category'));
    }

    /**
     * Creates a form to delete a Category entity by slug.
     *
     * @param mixed $slug The category slug
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($slug)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl(
                    'category_delete',
                    array('slug' => $slug))
            )
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
