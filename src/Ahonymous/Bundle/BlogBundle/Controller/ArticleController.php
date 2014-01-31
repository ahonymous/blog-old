<?php

namespace Ahonymous\Bundle\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ahonymous\Bundle\BlogBundle\Entity\Article;
use Ahonymous\Bundle\BlogBundle\Form\ArticleType;

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{

    /**
     * Lists all Article entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem(
            "Articles",
            $this->get("router")->generate("article")
        );

        $articles = $em->getRepository('AhonymousBlogBundle:Article')
            ->findAll();

        return $this->render(
            'AhonymousBlogBundle:Article:index.html.twig',
            array(
                'articles' => $articles,
            )
        );
    }
    /**
     * Creates a new Article entity.
     *
     */
    public function createAction(Request $request)
    {
        $article = new Article();

        $form = $this->createCreateForm($article);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'article_show',
                    array('slug' => $article->getSlug())
                )
            );
        }

        return $this->render(
            'AhonymousBlogBundle:Article:new.html.twig',
            array(
                'article' => $article,
                'form'   => $form->createView(),
            )
        );
    }

    /**
    * Creates a form to create a Article entity.
    *
    * @param Article $article The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Article $article)
    {
        $form = $this->createForm(new ArticleType(), $article, array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     *
     */
    public function newAction()
    {
        $article = new Article();
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem(
            "Article",
            $this->get("router")->generate("article")
        );
        $breadcrumbs->addItem(
            "New article",
            $this->get("router")->generate("article_new")
        );

        $form   = $this->createCreateForm($article);

        return $this->render(
            'AhonymousBlogBundle:Article:new.html.twig',
            array(
                'article' => $article,
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Article entity.
     *
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem(
            $this->get('translator')->trans('menu.home'),
            $this->get("router")->generate("home")
        );

        $article = $em
            ->getRepository('AhonymousBlogBundle:Article')
            ->findOneBy(array('slug' => $slug));

        if (!$article) {
            throw $this->createNotFoundException(
                'Unable to find Article entity.'
            );
        }

        $article->setViewed($article->getViewed() + 1);
        $em->flush();
        $breadcrumbs->addItem(
            $article->getName(),
            $this->get("router")
                ->generate("_single", array('slug' => $slug))
        );

        $deleteForm = $this->createDeleteForm($slug);

        return $this->render(
            'AhonymousBlogBundle:Article:show.html.twig',
            array(
                'article'      => $article,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     */
    public function editAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem(
            "Article",
            $this->get("router")->generate("article")
        );
        $breadcrumbs->addItem(
            "Edit article",
            $this->get("router")
                ->generate("article_edit", array('slug' => $slug))
        );

        $article = $em->getRepository('AhonymousBlogBundle:Article')
            ->findOneBy(array('slug' => $slug));

        if (!$article) {
            throw $this->createNotFoundException(
                'Unable to find Article entity.'
            );
        }

        $editForm = $this->createEditForm($article);
        $deleteForm = $this->createDeleteForm($slug);

        return $this->render(
            'AhonymousBlogBundle:Article:edit.html.twig',
            array(
                'article'      => $article,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
    * Creates a form to edit a Article entity.
    *
    * @param Article $article The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Article $article)
    {
        $form = $this->createForm(new ArticleType(), $article, array(
            'action' => $this->generateUrl(
                    'article_update',
                    array('slug' => $article->getSlug())
                ),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Article entity.
     *
     */
    public function updateAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('AhonymousBlogBundle:Article')
            ->findOneBy(array('slug' => $slug));
        $id = $article->getId();

        $categories = $request->get('article')['categories'];

        if (!$article) {
            throw $this->createNotFoundException(
                'Unable to find Article entity.
                ');
        }

        $deleteForm = $this->createDeleteForm($slug);
        $editForm = $this->createEditForm($article);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($categories as $id_category) {
                $category = $em->getRepository('AhonymousBlogBundle:Category')
                    ->findOneBy(array('id' => $id_category));
                $category->setCountArticles($category->getCountArticles()+1);
                $em->persist($category);
            }

            $em->flush();
            $updatedArticle = $em->getRepository('AhonymousBlogBundle:Article')
                ->findOneBy(array('id' => $id));

            return $this->redirect($this->generateUrl(
                    'article_show',
                    array('slug' => $updatedArticle->getSlug()))
            );
        }

        return $this->render(
            'AhonymousBlogBundle:Article:edit.html.twig',
            array(
                'article'      => $article,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }
    /**
     * Deletes a Article entity.
     *
     */
    public function deleteAction(Request $request, $slug)
    {
        $form = $this->createDeleteForm($slug);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('AhonymousBlogBundle:Article')
                ->findOneBy(array('slug' => $slug));

            if (!$article) {
                throw $this->createNotFoundException(
                    'Unable to find Article entity.'
                );
            }

            $em->remove($article);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('article'));
    }

    /**
     * Creates a form to delete a Article entity by slug.
     *
     * @param mixed $slug The entity slug
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($slug)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl(
                    'article_delete',
                    array('slug' => $slug))
            )
            ->setMethod('DELETE')
            ->add('delete', 'submit', array(
                    'label' => 'Delete',
                    'attr' => array('class' => "btn btn-danger"))
            )
            ->getForm()
        ;
    }
}
