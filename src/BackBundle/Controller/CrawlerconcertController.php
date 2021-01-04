<?php

namespace BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackBundle\Entity\Crawlerconcert;
use Symfony\Component\HttpFoundation\Request;
/**
 * Crawlerconcert controller.
 *
 */
class CrawlerconcertController extends Controller
{
    /**
     * Lists all crawlerconcert entities.
     *
     */
    public function indexAction ()
    {
        $em = $this->getDoctrine ()->getManager ();

        $this->get ( 'session' )->set ( 'menuactive' , "crawlerconcert" );
        $crawlerconcerts = $em->getRepository ( 'BackBundle:Crawlerconcert' )->findBy ( array( "enable" => 1 ) );

        return $this->render ( 'BackBundle:Crawlerconcert:index.html.twig' , array(
            'crawlerconcerts' => $crawlerconcerts ,
        ) );
    }

    /**
     * Finds and displays a crawlerconcert entity.
     *
     */
    public function showAction ( $id )
    {

        $em = $this->getDoctrine ()->getManager ();
        $crawlerconcert = $em->getRepository ( 'BackBundle:Crawlerconcert' )->find ( $id );

        return $this->render ( 'BackBundle:Crawlerconcert:show.html.twig' , array(
            'entity' => $crawlerconcert ,
        ) );
    }

  
    /**
     * Displays a form to edit an existing crawlerconcert entity.
     *
     */
     public function editAction(Request $request, $id)
     {
         $em = $this->getDoctrine()->getManager();
         $crawlerconcert = $em->getRepository('BackBundle:Crawlerconcert')->find($id);
         $old_picture = $crawlerconcert->getPicture();
         $editForm = $this->createForm('BackBundle\Form\CrawlerconcertType', $crawlerconcert);
         $editForm->handleRequest($request);
 
         if ($editForm->isSubmitted() && $editForm->isValid()) {
             $picture = $editForm->get('picture')->getData();
             if ($picture != null) {
                 $picture_Profil = $this->get('app.img_upload')->upload($picture);
                 $crawlerconcert->setPicture($picture_Profil);
                 $crawlerconcert->setTypePicture('local');
                 $this->get('app.img_upload')->deleteImage($old_picture);
                 
             } else {
                 $crawlerconcert->setPicture($old_picture);
             }
             
             $em->merge($crawlerconcert);
             $em->flush();
             $translator = $this->get('translator');
 
             $this->get('session')->getFlashBag()->add('crawlerconcertedit', $translator->trans('alert.edit'));
             return $this->redirectToRoute('crawlerconcert_show', array('id' => $id));
         }
 
         return $this->render('BackBundle:Crawlerconcert:new.html.twig', array(
             'id' => $id,
             'crawlerconcert' => $crawlerconcert,
             'form' => $editForm->createView(),
         ));
     }
 
 

    /**
     * Deletes a crawlerconcert entity.
     *
     */
    public function deleteAction ( $id )
    {

        $em = $this->getDoctrine ()->getManager ();
        $crawlerconcert = $em->getRepository ( 'BackBundle:Crawlerconcert' )->find ( $id );

        $crawlerconcert->setEnable ( 0 );
        $em->merge ( $crawlerconcert );
        $em->flush ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'crawlerconcertsuccess' , $translator->trans ( 'alert.delete' ) );

        return $this->redirectToRoute ( 'crawlerconcert_index' );

    }


}
