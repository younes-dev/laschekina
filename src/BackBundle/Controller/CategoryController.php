<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('BackBundle:Category')->findBy(array(),array('id'=>"DESC"));
        $this->get('session')->set('menuactive', "category");

        return $this->render('BackBundle:category:index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new category entity.
     *
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('BackBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('categorysuccess', $translator->trans('alert.add'));

            return $this->redirectToRoute('category_index');
        }

        return $this->render('BackBundle:category:edit.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing category entity.
     *
     */
    public function editAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('BackBundle:Category')->find($id);
        $editForm = $this->createForm('BackBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('categorysuccess', $translator->trans('alert.edit'));

            return $this->redirectToRoute('category_index');
        }

        return $this->render('BackBundle:category:edit.html.twig', array(
            'id' => $id,
            'category' => $category,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('BackBundle:Category')->find($id);

        if ($category != null) {
            $em->remove($category);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('categorydelete', $translator->trans('alert.delete'));

        return $this->redirectToRoute('category_index');
    }


}
