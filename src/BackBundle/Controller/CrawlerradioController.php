<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Crawlerradio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Crawlerradio controller.
 *
 */
class CrawlerradioController extends Controller
{
    /**
     * Lists all crawlerradio entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $this->get ( 'session' )->set ( 'menuactive' , "crawlerradio" );
        $crawlerradios = $em->getRepository('BackBundle:Crawlerradio')->findBy(array("enable" => 1));

        return $this->render('BackBundle:Crawlerradio:index.html.twig', array(
            'crawlerradios' => $crawlerradios,
        ));
    }
/**
     * Finds and displays a crawlerradio entity.
     *
     */
  

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crawlerradio = $em->getRepository('BackBundle:Crawlerradio')->find($id);

        return $this->render('BackBundle:Crawlerradio:show.html.twig', array(
            'entity' => $crawlerradio,
        ));
    }
      /**
     * Displays a form to edit an existing crawlerradio entity.
     *
     */
     public function editAction(Request $request, $id)
     {
         $em = $this->getDoctrine()->getManager();
         $crawlerradio = $em->getRepository('BackBundle:Crawlerradio')->find($id);
         $old_picture = $crawlerradio->getPicture();
         $editForm = $this->createForm('BackBundle\Form\CrawlerradioType', $crawlerradio);
         $editForm->handleRequest($request);
 
         if ($editForm->isSubmitted() && $editForm->isValid()) {
             $picture = $editForm->get('picture')->getData();
             if ($picture != null) {
                 $picture_Profil = $this->get('app.img_upload')->upload($picture);
                 $crawlerradio->setPicture($picture_Profil);
                 $crawlerradio->setTypePicture('local');
                 $this->get('app.img_upload')->deleteImage($old_picture);
                 
             } else {
                 $crawlerradio->setPicture($old_picture);
             }
             
             $em->merge($crawlerradio);
             $em->flush();
             $translator = $this->get('translator');
 
             $this->get('session')->getFlashBag()->add('crawlerradioedit', $translator->trans('alert.edit'));
             return $this->redirectToRoute('crawlerradio_show', array('id' => $id));
         }
 
         return $this->render('BackBundle:Crawlerradio:new.html.twig', array(
             'id' => $id,
             'crawlerradio' => $crawlerradio,
             'form' => $editForm->createView(),
         ));
     }
 

    /**
     * Deletes a crawlerradio entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crawlerradio = $em->getRepository('BackBundle:Crawlerradio')->find($id);

        $crawlerradio->setEnable(0);
        $em->merge($crawlerradio);
        $em->flush();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('crawlerradiosuccess', $translator->trans('alert.delete'));

        return $this->redirectToRoute('crawlerradio_index');


    }

   
}
