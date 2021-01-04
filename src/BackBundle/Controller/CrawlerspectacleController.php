<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Crawlerspectacle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Crawlerspectacle controller.
 *
 */
class CrawlerspectacleController extends Controller
{
    /**
     * Lists all crawlerspectacle entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $this->get ( 'session' )->set ( 'menuactive' , "crawlerspectacle" );
        $crawlerspectacles = $em->getRepository('BackBundle:Crawlerspectacle')->findBy(array('enable' => 1));

        return $this->render('BackBundle:Crawlerspectacle:index.html.twig', array(
            'crawlerspectacles' => $crawlerspectacles,
        ));
    }

  
    /**
     * Finds and displays a crawlerspectacle entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crawlerspectacle = $em->getRepository('BackBundle:Crawlerspectacle')->find($id);

        return $this->render('BackBundle:Crawlerspectacle:show.html.twig', array(
            'entity' => $crawlerspectacle,
        ));
    }

  
    /**
     * Displays a form to edit an existing crawlerspectacle entity.
     *
     */
     public function editAction(Request $request, $id)
     {
         $em = $this->getDoctrine()->getManager();
         $crawlerspectacle = $em->getRepository('BackBundle:Crawlerspectacle')->find($id);
         $old_picture = $crawlerspectacle->getPicture();
         $editForm = $this->createForm('BackBundle\Form\CrawlerspectacleType', $crawlerspectacle);
         $editForm->handleRequest($request);
 
         if ($editForm->isSubmitted() && $editForm->isValid()) {
             $picture = $editForm->get('picture')->getData();
             if ($picture != null) {
                 $picture_Profil = $this->get('app.img_upload')->upload($picture);
                 $crawlerspectacle->setPicture($picture_Profil);
                 $crawlerspectacle->setTypePicture('local');
                 $this->get('app.img_upload')->deleteImage($old_picture);
                 
             } else {
                 $crawlerspectacle->setPicture($old_picture);
             }
             
             $em->merge($crawlerspectacle);
             $em->flush();
             $translator = $this->get('translator');
             /*$typemedia = $this->get('session')->get('menuactive');
             $languge = $this->get('session')->get('languge');*/
 
             $this->get('session')->getFlashBag()->add('crawlerspectacleedit', $translator->trans('alert.edit'));
             return $this->redirectToRoute('crawlerspectacle_show', array('id' => $id));
         }
 
         return $this->render('BackBundle:Crawlerspectacle:new.html.twig', array(
             'id' => $id,
             'crawlerspectacle' => $crawlerspectacle,
             'form' => $editForm->createView(),
         ));
     }
 
 


   /**
     * Deletes a crawlerspectacle entity.
     *
     */
    public function deleteAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $crawlerspectacle = $em->getRepository('BackBundle:Crawlerspectacle')->find($id);

        $crawlerspectacle->setEnable(0);
        $em->merge($crawlerspectacle);
        $em->flush();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('crawlerspectaclesuccess', $translator->trans('alert.delete'));

        return $this->redirectToRoute('crawlerspectacle_index');
    }

   
}
