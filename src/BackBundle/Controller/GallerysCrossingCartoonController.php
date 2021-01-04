<?php

namespace BackBundle\Controller;

use BackBundle\Entity\GallerysCrossingCartoon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gallery controller.
 *
 */
class GallerysCrossingCartoonController extends Controller
{ 
    
     
    /**
     * Deletes a gallery entity.
     *
     */
    public function indexAction (Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $this->get ( 'session' )->set ( 'menuactive' , "gallery_crossing_cartoon" );
        $gallery = new GallerysCrossingCartoon();
        $form = $this->createForm ( 'BackBundle\Form\GallerysCrossingCartoonType' , $gallery );
        $form->handleRequest ( $request );

        if ( $form->isSubmitted () && $form->isValid () ) {
            $picture = $form->get ( 'path' )->getData ();
           
            if ( $picture != null ) {
                $picture = $this->get ( 'app.img_upload' )->upload ( $picture );
          
                $entity = $em->getRepository ( 'BackBundle:GallerysCrossingCartoon' )->findOneBy ( array('enable' => 1) );
                if($entity != null ){
                    $entity->setEnable(0);
                    $em->merge($entity);
                    $em->flush ();
                }

               
            }
            
            $this->get ( 'session' )->set ( 'picture' , $picture );

            $path = "upload/" . $picture;
            $this->container
                ->get ( 'liip_imagine.controller' )
                ->filterAction ( $request , $path , 'image_banner_back' );
                $gallery->setPath($picture);
                $gallery->setEnable(1);
                $em->persist($gallery);
                $em->flush ();
                $translator = $this->get ( 'translator' );
                $this->get ( 'session' )->getFlashBag ()->add ( 'GallerysCrossingCartoonsuccess' , $translator->trans ( 'alert.add' ) );
                return $this->redirectToRoute ( 'gallery_crossing_cartoon_index');
           
        }
        $gallerys = $em->getRepository ( 'BackBundle:GallerysCrossingCartoon' )->findAll (  );
     
        return $this->render ( 'BackBundle:gallerysCrossingCartoon:index.html.twig' , array(
            'gallerys' => $gallerys ,
          
            'form' => $form->createView () ,
        ) );


    } 
    /**
     * change  state  gallery entity.
     *
     */
    public function stateAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        
        $entity = $em->getRepository ( 'BackBundle:GallerysCrossingCartoon' )->findOneBy ( array('enable' => 1) );
        if($entity != null ){
            $entity->setEnable(0);
            $em->merge($entity);
            $em->flush ();
        }
        $gallery = $em->getRepository ( 'BackBundle:GallerysCrossingCartoon' )->find ( $id );

        if ( $gallery->getEnable () == 1 ) {
            $gallery->setEnable ( 0 );
        } else {
            $gallery->setEnable ( 1 );
        }
        $em->merge ( $gallery );
        $em->flush ();

        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'GallerysCrossingCartoonsuccess' , $translator->trans ( 'alert.state' ) );
            return $this->redirectToRoute ( 'gallery_crossing_cartoon_index');
    }
   
    
    /**
     * Deletes a gallery entity.
     *
     */
    public function deleteAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $gallery = $em->getRepository ( 'BackBundle:GallerysCrossingCartoon' )->find ( $id );

        if ( $gallery ) {
            $this->get ( 'app.img_upload' )->deleteImage ( $gallery->getPath () );

            $em->remove ( $gallery );
            $em->flush ();

        }
        
        
        $entities = $em->getRepository ( 'BackBundle:GallerysCrossingCartoon' )->findBy ( array('enable' => 1 ) );
        if($entities == null ){
            $gallery = $em->getRepository ( 'BackBundle:GallerysCrossingCartoon' )->findOneBy ( array(),array(),1 );
            if( $gallery != null ){
                $gallery->setEnable ( 1 );
                $em->merge ( $gallery );
                $em->flush ();
            }
        
        }
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'GallerysCrossingCartoonsuccess' , $translator->trans ( 'alert.delete' ) );
       
        return $this->redirectToRoute ( 'gallery_crossing_cartoon_index');
    }
 
   
  
}
