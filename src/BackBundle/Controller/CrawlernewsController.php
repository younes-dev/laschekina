<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Crawlernews;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Crawlernews controller.
 *
 */
class CrawlernewsController extends Controller
{
    /**
     * Lists all crawlernews entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $this->get ( 'session' )->set ( 'menuactive' , "crawlernews" );
        $crawlernews = $em->getRepository('BackBundle:Crawlernews')->findBy(array("enable" => 1));

        return $this->render('BackBundle:Crawlernews:index.html.twig', array(
            'crawlernews' => $crawlernews,
        ));

    }

    /**
     * Finds and displays a crawlernews entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crawlercinema = $em->getRepository('BackBundle:Crawlernews')->find($id);

        return $this->render('BackBundle:Crawlernews:show.html.twig', array(
            'entity' => $crawlercinema,
        ));
    }

         /**
     * Displays a form to edit an existing crawlernews entity.
     *
     */
     public function editAction(Request $request, $id)
     {
         $em = $this->getDoctrine()->getManager();
         $crawlernews = $em->getRepository('BackBundle:Crawlernews')->find($id);
         $old_picture = $crawlernews->getPicture();
         $editForm = $this->createForm('BackBundle\Form\CrawlernewsType', $crawlernews);
         $editForm->handleRequest($request);
 
         if ($editForm->isSubmitted() && $editForm->isValid()) {
             $picture = $editForm->get('picture')->getData();
             if ($picture != null) {
                 $picture_Profil = $this->get('app.img_upload')->upload($picture);
                 $crawlernews->setPicture($picture_Profil);
                 $crawlernews->setTypePicture('local');
                 $this->get('app.img_upload')->deleteImage($old_picture);
                 
             } else {
                 $crawlernews->setPicture($old_picture);
             }
             
             $em->merge($crawlernews);
             $em->flush();
             $translator = $this->get('translator');
 
             $this->get('session')->getFlashBag()->add('crawlernewsedit', $translator->trans('alert.edit'));
             return $this->redirectToRoute('crawlernews_show', array('id' => $id));
         }
 
         return $this->render('BackBundle:Crawlernews:new.html.twig', array(
             'id' => $id,
             'crawlernews' => $crawlernews,
             'form' => $editForm->createView(),
         ));
     }

    /**
     * Deletes a crawlernews entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crawlernews = $em->getRepository('BackBundle:Crawlernews')->find($id);

        if($crawlernews != null) {
            $crawlernews->setEnable ( 0 );
            $em->merge ( $crawlernews );
            $em->flush ();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('crawlernewssuccess', $translator->trans('alert.delete'));

        return $this->redirectToRoute('crawlernews_index');


    }

  }
