<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Content controller.
 *
 */
class ContentController extends Controller
{
    /**
     * Lists all content entities.
     *
     */
    public function indexAction($type_content)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('session')->set('menuactive', $type_content);

        $contents = $em->getRepository('BackBundle:Content')->getContentList($type_content);

        return $this->render('BackBundle:Content:index.html.twig', array(
            'contents' => $contents,
        ));
    }



    /**
     * Displays a form to edit an existing content entity.
     *
     */
    public function editAction(Request $request,  $id)
    {

        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository('BackBundle:Content')->find($id);

      $editForm = $this->createForm('BackBundle\Form\ContentType' , $content);


       $editForm->handleRequest($request);



        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('conditionssuccess', $translator->trans('alert.edit'));

            return $this->redirectToRoute('content_index',array("type_content"=>$this->get('session')->get('menuactive')));
        }

        return $this->render('BackBundle:Content:edit.html.twig', array(
            'content' => $content,
            'edit_form' => $editForm->createView(),
        ));
    }




}
