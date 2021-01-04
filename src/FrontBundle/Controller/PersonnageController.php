<?php

/**
 * Controller for home page.
 *
 * @package FrontBundle
 * @author Mouadh Ben Alaya
 */

namespace FrontBundle\Controller;

use BackBundle\Entity\Archetype;
use BackBundle\Entity\CollectionmediaPersonnage;
use BackBundle\Entity\Box;
use BackBundle\Entity\CommentaireMedia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for home page.
 *
 * @package FrontBundle
 * @author Mouadh Ben Alaya
 */
class PersonnageController extends Controller
{
    /**
     * Profil personnage
     * @return Response A Response instance
     **/
    public function profilAction ( $activeTab , $id )
    {

        $em = $this->getDoctrine ()->getManager ();

        $personnage  = $em->getRepository ( 'BackBundle:PersonnageMedia' )->find ( $id );
        $listePhotos  = $em->getRepository ( 'BackBundle:CollectionmediaPersonnage' )->findBy ( array('personnageMedia' => $id , 'typeMedia' => 'picture'),array('id' => 'DESC') );
        $listeVideosUrl  = $em->getRepository ( 'BackBundle:CollectionmediaPersonnage' )->findBy ( array('personnageMedia' => $id , 'typeMedia' => 'Url'),array('id' => 'DESC') );
        $listeVideoMp4  = $em->getRepository ( 'BackBundle:CollectionmediaPersonnage' )->findBy ( array('personnageMedia' => $id , 'typeMedia' => 'mp4'),array('id' => 'DESC') );
         $idLiver =   $personnage->getCollectionMedia()->getId();
        
       // $listeAutrepersonnage  = $em->getRepository ( 'BackBundle:PersonnageMedia' )->findPersonnageByBookFixe ($idLiver , $personnage->getId(), 5 );
        $listeBookBD = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByLivreBD("videos",  $personnage->getCollectionMedia()->getId() ,'liver_bd');

        return $this->render ( 'FrontBundle:Personnage:index.html.twig' , array(
            'listeBookBD' => $listeBookBD ,
            'personnage' => $personnage ,
            'listeAutrepersonnage' => null,
            'activeTab' => $activeTab ,
            'book' => $personnage->getCollectionMedia(),
            'listePhotos' => $listePhotos ,
            'listeVideoMp4' => $listeVideoMp4,
            'listeVideosUrl' => $listeVideosUrl


        ) );
    }



    /**
     * crossing Cartoon
     *
     * @param Request $request The current request. 
     *
     * @return Response A Response instance
     **/
    public function crossingCartoonAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
            
           $personnagesMedia = $em->getRepository('BackBundle:PersonnageMedia')->findBy(array('beauxPersonnages' => 1));
           $listeVIP = $em->getRepository ( 'UserBundle:User' )->findByRoleRANDFixe ( "ROLE_VIP" , 5 );
           $galleryBanner = $em->getRepository ( 'BackBundle:GallerysCrossingCartoon' )->findOneBy ( array('enable' => 1 ) );
      
         return $this->render('FrontBundle:Personnage:crossing_cartoon.html.twig', array(
           //  "livre" => $livre,
             'listeVIP' => $listeVIP ,
             "personnagesMedia" => $personnagesMedia, 
             'gallerybanner' => $galleryBanner
         ));
    
    }



    /**
     * crossing Cartoon
     *
     * @param Request $request The current request.
     *
     * @return Response A Response instance
     **/
    public function crossingStudioAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();

        $listeBookImagesBD = $em->getRepository ( 'BackBundle:BookImagesBD' )->getscrollInfini(6);
        $listeVIP = $em->getRepository ( 'UserBundle:User' )->findByRoleRANDFixe ( "ROLE_VIP" , 5 );
        $gallerysBanners = $em->getRepository ( 'BackBundle:Collectionmedia' )->findBy(array('type' => 'photos' , 'showPageBanniere' => 1) ,array(), 14);

        return $this->render('FrontBundle:Personnage:crossing_studio.html.twig', array(

             'listeBookImagesBD' => $listeBookImagesBD,
             'gallerys_banner' => $gallerysBanners,
             'listeVIP' => $listeVIP
         ));

    }


    /**
     * Page Add  Picture
     * @param Request $request The current request.
     * @param string $activeTab The Tab Active identifier.
     *
     * @return Response A Response instance
     **/

    /******kamilt il news et rdv ki tkamil il ba9i fasa5a il function hathi*****/

    public function collectionAddItemAction ( Request $request , $id   )
    {

        $em = $this->getDoctrine ()->getManager ();
         $translator = $this->get ( 'translator' );
        $personnage  = $em->getRepository ( 'BackBundle:PersonnageMedia' )->find ( $id );

        $media = new CollectionmediaPersonnage();
        
        /** url de de vidéo  **/
        $typeVideo = $request->get ( 'typeVideo' );
               
         switch ( $typeVideo ) {
            case 1:
                $url = $request->get ( 'url' );
                $media->setTypeMedia ( 'Url' );
                if ( $url != null ) {
                    $urlVideo = $this->get('app.img_upload')->getIdvideo ( $url );
                    $media->setSourcevideo ( $urlVideo[ 0 ] );
                    $media->setUrl ( $urlVideo[ 1 ] );
                }
                break;
            case 2:
                $media->setTypeMedia ( 'mp4' );
                $picture = $_FILES[ 'mediaVidoe' ][ 'name' ];
                $tmp_name_array = $_FILES[ 'mediaVidoe' ][ 'tmp_name' ];
                $size_array = $_FILES[ 'mediaVidoe' ][ 'size' ];
                if ( $size_array > 0 ) {
                    $extension = pathinfo ( $picture , PATHINFO_EXTENSION );
                    $picture = md5 ( uniqid () ) . "." . $extension;
                    move_uploaded_file ( $tmp_name_array , 'upload/' . $picture );
                    $media->setPath ( $picture );
                }

                break;
            case 4:
                $media->setTypeMedia ( 'picture' );
                 
                $picture = $_FILES[ 'picture' ][ 'name' ];
                $tmp_name_array = $_FILES[ 'picture' ][ 'tmp_name' ];
                $size_array = $_FILES[ 'picture' ][ 'size' ];
                if ( $size_array > 0 ) {
                    $extension = pathinfo ( $picture , PATHINFO_EXTENSION );
                    $picture = md5 ( uniqid () ) . "." . $extension;
                    move_uploaded_file ( $tmp_name_array , 'upload/' . $picture );
                    $media->setPicture ( $picture );
                }
                break;
        }

        /** title   **/
        $title = $request->request->get ( 'title' );
        $media->setTitle ( $title );

        $media->setPersonnageMedia ( $personnage );
     
        $em->persist ( $media );
        $em->flush ();

             $this->get ( 'session' )->getFlashBag ()->add ( 'collectionPersonnagesuccess' , $translator->trans ( 'alert.add' ) );
      
        return $this->redirectToRoute ( 'front_end_personnage_profile' , array('activeTab' =>'media' , 'id' => $id ) );


    }
 

    public function collectionDeleteItemAction(Request $request)
    {
        $id = $request->get('id-item');
        $em = $this->getDoctrine ()->getManager ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'collectionPersonnagesuccess' , $translator->trans ( 'alert.delete' ) );
        $entity  = $em->getRepository ( 'BackBundle:CollectionmediaPersonnage' )->find ( $id );
        $idPersonnage = $entity->getPersonnageMedia()->getId();
        if($entity != null ){
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirectToRoute ( 'front_end_personnage_profile' , array( 'activeTab' =>'media' , 'id' => $idPersonnage ) );

    }

    public function collectionEditItemDescriptionAction(Request $request , $activeTab , $id)
    {
        $description = $request->get('description');
        $em = $this->getDoctrine ()->getManager ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'collectionDescriptionPersonnage' , $translator->trans ( 'alert.edit' ) );
        $entity  = $em->getRepository ( 'BackBundle:PersonnageMedia' )->find ( $id );
             if($entity != null ){
            $entity->setDescription($description);
            $em->merge($entity);
            $em->flush();
        }
        return $this->redirectToRoute ( 'front_end_personnage_profile' , array( 'activeTab' => $activeTab , 'id' => $id ) );

    }


      /**
     * Page ajax List media vip.
     *
     * @param Request $request The current request.
     *
     * @return JsonResponse A JsonResponse instance
     **/
    public function addOrRemoveboxajaxAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();

        $id = $request->get ( 'id' );
        $typeAction = $request->get ( 'typeAction' );
        $userCourant = $this->getUser ();
        $entity = null;

        $item = $em->getRepository ( 'BackBundle:PersonnageMedia' )->find ( $id );
        
        if ( $typeAction == "add" ) {
            $entity = new Box();

            $entity->setMembre ( $userCourant );

            $entity->setPersonnageMedia ( $item );
           
            $entity->setTypebox ( "crossing-cartoon" );

            $em->persist ( $entity );

        } else {
           
            $User_in_box = $em->getRepository ( 'BackBundle:Box' )->findOneBy ( array( "personnageMedia" => $item->getId () , "membre" => $userCourant->getId () , "typebox" => "crossing-cartoon" ) );

            $em->remove ( $User_in_box );
        }
        $em->flush ();

        $boxHtml = $this->renderView ( 'FrontBundle:Personnage:ajaxbox.html.twig' , array( 'item' => $item , 'itemInBox' => $entity  ) );

        return new JsonResponse( array(
            'boxHtml' => $boxHtml
        ) );
    }

    
}
