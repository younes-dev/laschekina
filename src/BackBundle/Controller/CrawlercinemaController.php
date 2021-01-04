<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Crawlercinema;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Crawlercinema controller.
 *
 */
class CrawlercinemaController extends Controller
{
    /**
     * Lists all crawlercinema entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $this->get ( 'session' )->set ( 'menuactive' , "crawlercinema" );
        $crawlercinemas = $em->getRepository('BackBundle:Crawlercinema')->findBy(array("enable" => 1));

        return $this->render('BackBundle:Crawlercinema:index.html.twig', array(
            'crawlercinemas' => $crawlercinemas,
        ));
    }

 
    /**
     * Finds and displays a crawlercinema entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crawlercinema = $em->getRepository('BackBundle:Crawlercinema')->find($id);

        return $this->render('BackBundle:Crawlercinema:show.html.twig', array(
            'entity' => $crawlercinema,
        ));
    }

    
    /**
     * Displays a form to edit an existing crawlercinema entity.
     *
     */
     public function editAction(Request $request, $id)
     {
         $em = $this->getDoctrine()->getManager();
         $crawlercinema = $em->getRepository('BackBundle:Crawlercinema')->find($id);
         $old_picture = $crawlercinema->getPicture();
         $editForm = $this->createForm('BackBundle\Form\CrawlercinemaType', $crawlercinema);
         $editForm->handleRequest($request);
 
         if ($editForm->isSubmitted() && $editForm->isValid()) {
             $picture = $editForm->get('picture')->getData();
             if ($picture != null) {
                 $picture_Profil = $this->get('app.img_upload')->upload($picture);
                 $crawlercinema->setPicture($picture_Profil);
                 $crawlercinema->setTypePicture('local');
                 $this->get('app.img_upload')->deleteImage($old_picture);
                 
             } else {
                 $crawlercinema->setPicture($old_picture);
             }
             
             $em->merge($crawlercinema);
             $em->flush();
             $translator = $this->get('translator');
             /*$typemedia = $this->get('session')->get('menuactive');
             $languge = $this->get('session')->get('languge');*/
 
             $this->get('session')->getFlashBag()->add('crawlercinemaedit', $translator->trans('alert.edit'));
             return $this->redirectToRoute('crawlercinema_show', array('id' => $id));
         }
 
         return $this->render('BackBundle:Crawlercinema:new.html.twig', array(
             'id' => $id,
             'crawlercinema' => $crawlercinema,
             'form' => $editForm->createView(),
         ));
     }
 
 


    /**
     * Deletes a crawlercinema entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crawlercinema = $em->getRepository('BackBundle:Crawlercinema')->find($id);

        $crawlercinema->setEnable(0);
        $em->merge($crawlercinema);
        $em->flush();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('crawlercinemasuccess', $translator->trans('alert.delete'));

        return $this->redirectToRoute('crawlercinema_index');

    }

}
