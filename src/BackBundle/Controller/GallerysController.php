<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Gallerys;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gallery controller.
 *
 */
class GallerysController extends Controller
{
    /**
     * Lists all gallery entities.
     *
     */
    public function indexAction ( Request $request , $type_gallery , $id_media )
    {
        $em = $this->getDoctrine ()->getManager ();
        $this->get ( 'session' )->set ( 'menuactive' , $type_gallery );
        $this->get ( 'session' )->set ( 'step' , 1 );
        $this->get ( 'session' )->set ( 'idMedia' , $id_media );
        $gallery = new Gallerys();
        $form = $this->createForm ( 'BackBundle\Form\GallerysType' , $gallery );
        $form->handleRequest ( $request );

        if ( $form->isSubmitted () && $form->isValid () ) {
            $picture = $form->get ( 'picture' )->getData ();
            $coverpicture = $form->get ( 'coverpicture' )->getData ();
            if ( $picture != null ) {
                $picture = $this->get ( 'app.img_upload' )->upload ( $picture );
            }
            if ( $coverpicture != null ) {
                $picture = $this->get ( 'app.img_upload' )->upload ( $coverpicture );
            }
            $this->get ( 'session' )->set ( 'picture' , $picture );

            $path = "upload/" . $picture;
            $this->container
                ->get ( 'liip_imagine.controller' )
                ->filterAction ( $request , $path , 'image_banner_back' );
            $this->get ( 'session' )->set ( 'step' , 2 );
            if ( $type_gallery == "gallery_media" ) {
                $gallerys = $em->getRepository ( 'BackBundle:Gallerys' )->getGalleryMediaList ( $type_gallery , $id_media );

            } else {
                $gallerys = $em->getRepository ( 'BackBundle:Gallerys' )->getGalleryList ( $type_gallery );
            }
            return $this->render ( 'BackBundle:gallerys:index.html.twig' , array(
                'gallerys' => $gallerys ,
                'idMedia' => $id_media ,
                'form' => $form->createView () ,
            ) );
        }
        if ( $type_gallery == "gallery_media" ) {
            $gallerys = $em->getRepository ( 'BackBundle:Gallerys' )->getGalleryMediaList ( $type_gallery , $id_media );

        } else {
            $gallerys = $em->getRepository ( 'BackBundle:Gallerys' )->getGalleryList ( $type_gallery );
        }
        return $this->render ( 'BackBundle:gallerys:index.html.twig' , array(
            'idMedia' => $id_media ,
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
        $gallery = $em->getRepository ( 'BackBundle:Gallerys' )->find ( $id );

        if ( $gallery->getState () == 1 ) {
            $gallery->setState ( 0 );
        } else {
            $gallery->setState ( 1 );
        }
        $em->merge ( $gallery );
        $em->flush ();

        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'gallerysuccess' , $translator->trans ( 'alert.state' ) );
        $type_gallery = $this->get ( 'session' )->get ( 'menuactive' );
        if ( $gallery->getMedia () == null )
            return $this->redirectToRoute ( 'gallerys_index' , array( 'type_gallery' => $type_gallery ) );
        else
            return $this->redirectToRoute ( 'gallerys_index' , array( 'type_gallery' => $type_gallery , 'id_media' => $gallery->getMedia ()->getId () ) );
    }

    /**
     * Deletes a gallery entity.
     *
     */
    public function deleteAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $gallery = $em->getRepository ( 'BackBundle:Gallerys' )->find ( $id );

        if ( $gallery ) {
            $this->get ( 'app.img_upload' )->deleteImage ( $gallery->getPicture () );

            $em->remove ( $gallery );
            $em->flush ();

        }
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'gallerysdelete' , $translator->trans ( 'alert.delete' ) );
        $type_gallery = $this->get ( 'session' )->get ( 'menuactive' );
        $idMedia = $this->get ( 'session' )->get ( 'idMedia' );
        return $this->redirectToRoute ( 'gallerys_index' , array( 'type_gallery' => $type_gallery ,'id_media'=>$idMedia ) );
    }


    /**
     * Uploadpicture
     *
     * @param Request $request
     * @return Response
     */
    public function UploadpictureAction ()
    {
        $em = $this->getDoctrine ()->getManager ();

        try {
            $images = $this->get ( 'app.img_upload' )->getImages ();
        } catch ( Exception $e ) {
            $this->get ( 'app.img_upload' )->outputJSON ( 'failure' );
            return;
        }

        // if no images found
        if ( $images != null and count ( $images ) === 0 ) {
            $this->get ( 'app.img_upload' )->outputJSON ( 'failure' );
            return;
        }

        // should always be one file (when posting async)
        $image = $images[ 0 ];
        $file = $this->get ( 'app.img_upload' )->saveFile ( $image[ 'output' ][ 'data' ] , $image[ 'output' ][ 'name' ] );

        // echo results
        $this->get ( 'app.img_upload' )->outputJSON ( 'success' , $file[ 'name' ] , $file[ 'path' ] );
        $type_gallery = $this->get ( 'session' )->get ( 'menuactive' );
        $picture = $this->get ( 'session' )->get ( 'picture' );
        $typeGallerys = $em->getRepository ( 'BackBundle:Typegallery' )->findOneBy ( array( 'title' => $type_gallery ) );
        $gallery = new Gallerys();
        $gallery->setState ( 1 );
        $gallery->setTypegallery ( $typeGallerys );
        if ( $type_gallery == 'gallery_home' ) {
            $gallery->setCoverpicture ( $picture );
            $gallery->setLosangepicture ( $file[ 'name' ] );
        } else {
            if ( $type_gallery == "gallery_media" ) {

                $Media = $em->getRepository ( 'BackBundle:Medias' )->find ($this->get ( 'session' )->get ( 'idMedia' )  );
                $gallery->setMedia ($Media);
            }
                $gallery->setPicture ( $picture );
            $gallery->setBannerpicture ( $file[ 'name' ] );
        }
        $em->persist ( $gallery );
        $em->flush ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'gallerysuccess' , $translator->trans ( 'alert.add' ) );

        exit();
    }
/*******************************    gallerys Carousel Home     **********************************/
    /**
     * Lists all gallerys Carousel Home  entities.
     *
     */
    public function gallerysCarouselHomeAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $this->get ( 'session' )->set ( 'menuactive' , "gallerysCarouselIndex" );
        $gallerys = $em->getRepository ( 'BackBundle:Collectionmedia' )->findBy(array("type" => "photos" , "showPageBanniere" => 1  ),array("id" => "DESC") );
      
        $personnageMedias = $em->getRepository ( 'BackBundle:PersonnageMedia' )->findBy(array("showPageBanniere" => 1  ),array("id" => "DESC") );
        
        if($request->getMethod() == "POST"){
            $gallerys = $em->getRepository ( 'BackBundle:Collectionmedia' )->findBy(array("type" => "photos" ),array("id" => "DESC") , 30);
        }
        return $this->render ( 'BackBundle:gallerys:gallerysCarouselIndex.html.twig' , array(
            'gallerys' => $gallerys ,
            'personnageMedias' => $personnageMedias,
        ) );
    }
    /**
     * Lists edit gallerys Carousel Home entities.
     *
     */
    public function editgallerysCarouselHomeAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $gallerys = $em->getRepository ( 'BackBundle:Collectionmedia' )->findBy(array("type" => "photos" , "showPageBanniere" => 1  ),array("id" => "DESC") );
        $personnageMedias = $em->getRepository ( 'BackBundle:PersonnageMedia' )->findBy(array("showPageBanniere" => 1  ),array("id" => "DESC") );
        $numbre = 100 ;
        if($gallerys != null )
        $numbre = 100 - count($gallerys);


        $newgallerys = $em->getRepository ( 'BackBundle:Collectionmedia' )->findgallerysCarouselHomeMedia("photos" , $numbre);
        $newpersonnageMedias = $em->getRepository ( 'BackBundle:PersonnageMedia' )->findgallerysCarouselHomeMedia();
       
      
        if($request->getMethod() == "POST"){
                /** gestion les show VIP  */
          
            $obj_merged = (object) array_merge((array) $gallerys, (array) $newgallerys);
            foreach ($obj_merged as $value){
                $resultat = $request->get('item-'.$value->getId());
                 if($resultat == "on"){
                    $value->setShowPageBanniere($resultat);
               $em->merge($value);
               $em->flush();
                }
            }
                /** gestion les show personnage  */
            $obj_merged = (object) array_merge((array) $personnageMedias, (array) $newpersonnageMedias);
            foreach ($obj_merged as $value){
                $resultat = $request->get('personnage-'.$value->getId());
                 if($resultat == "on"){
                    $value->setShowPageBanniere($resultat);
               $em->merge($value);
               $em->flush();
                }
            }
            $translator = $this->get ( 'translator' );
            $this->get ( 'session' )->getFlashBag ()->add ( 'gallerysCarouselIndexsuccess' , $translator->trans ( 'alert.add' ) );
            return $this->redirectToRoute ( 'gallerys_carousel_index' );
        }


        return $this->render ( 'BackBundle:gallerys:editgallerysCarouselIndex.html.twig' , array(
            'gallerys' => $gallerys ,
            'personnageMedias' => $personnageMedias,
            'newgallerys' => $newgallerys ,
            'newpersonnageMedias' => $newpersonnageMedias
        ) );
    }

    /**
     * Lists delete gallerys Carousel  Home entities.
     *
     */
    public function deletegallerysCarouselHomeAction ( $id ,$type )
    {
        $em = $this->getDoctrine ()->getManager ();
        if($type == 'vip'){
            $gallery = $em->getRepository ( 'BackBundle:Collectionmedia' )->find($id);
            $gallery->setShowPageBanniere(0);
        }else{
            $gallery = $em->getRepository ( 'BackBundle:PersonnageMedia' )->find($id);
            $gallery->setShowPageBanniere(0);
        }

        $em->merge($gallery);
        $em->flush ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'gallerysCarouselIndexsuccess' , $translator->trans ( 'alert.delete' ) );
        return $this->redirectToRoute ( 'gallerys_carousel_index' );
    }
}
