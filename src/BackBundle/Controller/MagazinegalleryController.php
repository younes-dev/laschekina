<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Magazinegallery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Magazinegallery controller.
 *
 */
class MagazinegalleryController extends Controller
{
    /**
     * Lists all magazinegallery entities.
     *
     */
    public function indexAction( Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $this->get ( 'session' )->set ( 'menuactive' , "magazinegallery" );
        $magazinegallerie = new Magazinegallery();
        $form = $this->createForm ( 'BackBundle\Form\MagazinegalleryType' , $magazinegallerie );
        $form->handleRequest ( $request );

        if ( $form->isSubmitted () && $form->isValid () ) {
           $picture = $form->get ( 'picture' )->getData ();
            if ( $picture != null ) {
                $itemMagazinegalleries = $em->getRepository('BackBundle:Magazinegallery')->findBy(array("state" => 1 , "typegallerys" => "admin"));
               foreach ($itemMagazinegalleries as $value){
                   $entity = $em->getRepository('BackBundle:Magazinegallery')->find($value->getId()) ;
                   $entity->setState(0);
                   $em->merge($entity);
                   $em->flush();
               }
                $picture = $this->get ( 'app.img_upload' )->upload ( $picture );

            $magazinegallerie->setPicture($picture);
            $magazinegallerie->setUsercreate($this->getUser());
            $magazinegallerie->setTypegallerys("admin");
            $em->persist($magazinegallerie);
            $em->flush();
             $translator = $this->get ( 'translator' );
            $this->get ( 'session' )->getFlashBag ()->add ( 'magazinegallery' , $translator->trans ( 'alert.add' ) );
            }
        }
        $magazinegalleries = $em->getRepository('BackBundle:Magazinegallery')->findBy(array("typegallerys" => "admin"),array("state" => "DESC"));

        return $this->render('BackBundle:magazinegallery:index.html.twig', array(
            'magazinegalleries' => $magazinegalleries,
            'form' => $form->createView () ,
        ));
    }
    /**
     * change  state  gallery entity.
     *
     */
    public function stateAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $gallery = $em->getRepository ( 'BackBundle:Magazinegallery' )->find ( $id );

        if ( $gallery->getState () == 1 ) {
            $gallery->setState ( 0 );
        } else {
            $itemMagazinegalleries = $em->getRepository('BackBundle:Magazinegallery')->findBy(array("state" => 1 , "typegallerys" => "admin"));
            foreach ($itemMagazinegalleries as $value){
                $entity = $em->getRepository('BackBundle:Magazinegallery')->find($value->getId()) ;
                $entity->setState(0);
                $em->merge($entity);
                $em->flush();
            }
            $gallery->setState ( 1 );
        }
        $em->merge ( $gallery );
        $em->flush ();

        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'magazinegallery' , $translator->trans ( 'alert.state' ) );
    
        return $this->redirectToRoute ( 'magazinegallery_index'  );
    }

    /**
     * Deletes a gallery entity.
     *
     */
    public function deleteAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();

        $gallery = $em->getRepository ( 'BackBundle:Magazinegallery' )->find ( $id );

        if ( $gallery ) {
            $this->get ( 'app.img_upload' )->deleteImage ( $gallery->getPicture () );

            $em->remove ( $gallery );
            $em->flush ();

        }
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'magazinegallery' , $translator->trans ( 'alert.delete' ) );

        return $this->redirectToRoute ( 'magazinegallery_index' );
    }

}
