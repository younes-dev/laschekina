<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Crawlerprogramme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Crawlerprogramme controller.
 *
 */
class CrawlerprogrammeController extends Controller
{
    /**
     * Lists all crawlerprogramme entities.
     *
     */
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();

        $this->get ( 'session' )->set ( 'menuactive' , "crawlerprogramme" );
        $crawlerprogramme = $em->getRepository('BackBundle:Crawlerprogramme')->findBy(array("enable" => 1));

        return $this->render('BackBundle:Crawlerprogramme:index.html.twig', array(
            'crawlerprogrammes' => $crawlerprogramme,
        ));
    }

    /**
     * Finds and displays a crawlerprogramme entity.
     *
     */
    public function showAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $crawlerprogramme = $em->getRepository('BackBundle:Crawlerprogramme')->find($id);

        return $this->render('BackBundle:Crawlerprogramme:show.html.twig', array(
            'entity' => $crawlerprogramme,
        ));

    }

           /**
     * Displays a form to edit an existing crawlerprogramme entity.
     *
     */
     public function editAction(Request $request, $id)
     {
         $em = $this->getDoctrine()->getManager();
         $crawlerprogramme = $em->getRepository('BackBundle:Crawlerprogramme')->find($id);
         $old_picture = $crawlerprogramme->getPicture();
         $editForm = $this->createForm('BackBundle\Form\CrawlerprogrammeType', $crawlerprogramme);
         $editForm->handleRequest($request);
 
         if ($editForm->isSubmitted() && $editForm->isValid()) {
             $picture = $editForm->get('picture')->getData();
             if ($picture != null) {
                 $picture_Profil = $this->get('app.img_upload')->upload($picture);
                 $crawlerprogramme->setPicture($picture_Profil);
                 $crawlerprogramme->setTypePicture('local');
                 $this->get('app.img_upload')->deleteImage($old_picture);
                 
             } else {
                 $crawlerprogramme->setPicture($old_picture);
             }
             
             $em->merge($crawlerprogramme);
             $em->flush();
             $translator = $this->get('translator');
 
             $this->get('session')->getFlashBag()->add('crawlerprogrammeedit', $translator->trans('alert.edit'));
             return $this->redirectToRoute('crawlerprogramme_show', array('id' => $id));
         }
 
         return $this->render('BackBundle:Crawlerprogramme:new.html.twig', array(
             'id' => $id,
             'crawlerprogramme' => $crawlerprogramme,
             'form' => $editForm->createView(),
         ));
     }

  /**
     * Deletes a crawlerprogramme entity.
     *
     */
    public function deleteAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $crawlerprogramme = $em->getRepository('BackBundle:Crawlerprogramme')->find($id);
        
        $crawlerprogramme->setEnable(0);
        $em->merge($crawlerprogramme);
        $em->flush();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('crawlerprogrammesuccess', $translator->trans('alert.delete'));

        return $this->redirectToRoute('crawlerprogramme_index');

    }
}
