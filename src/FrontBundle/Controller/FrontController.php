<?php

/**
 * Controller for home page.
 *
 * @package FrontBundle
 * @author Mouadh Ben Alaya
 */

namespace FrontBundle\Controller;

use BackBundle\Entity\Archetype;
use BackBundle\Entity\Box;
use BackBundle\Entity\CommentaireMedia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for home page.
 *
 * @package FrontBundle
 * @author Mouadh Ben Alaya
 */
class FrontController extends Controller
{
    /**
     * Home page.
     *
     * @param Request $request The current request.
     *
     * @return Response A Response instance
     **/
    public function homeAction ( Request $request , $page )
    {

        $em = $this->getDoctrine ()->getManager ();
        $session = $request->getSession ();
        $session->set ( 'activerouteCourant' , "accueil" );
        $session->set ( 'page' , $page );
        $language = $request->getLocale ();

        $gallerysIndexes = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 4 , 'gallery_home' );

        $userCourant = $this->getUser ();
        $viPouup = null;
        $vipUsers = null;
        $alertBoxMedia = null;

        /***** Crawler *****/
        $crawlercinemas = $em->getRepository ( 'BackBundle:Crawlercinema' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( "id" => "DESC" ) , 5 );
        $crawlernews = $em->getRepository ( 'BackBundle:Crawlernews' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( "id" => "DESC" ) , 5 );
        $crawlerprogramme = $em->getRepository ( 'BackBundle:Crawlerprogramme' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( "id" => "DESC" ) , 5 );
        $crawlerradios = $em->getRepository ( 'BackBundle:Crawlerradio' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( "id" => "DESC" ) , 5 );
        $crawlerspectacles = $em->getRepository ( 'BackBundle:Crawlerspectacle' )->findBy ( array( 'enable' => 1 , "pays" => $language ) , array( "id" => "DESC" ) , 5 );
        $crawlerconcerts = $em->getRepository ( 'BackBundle:Crawlerconcert' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( "id" => "DESC" ) , 5 );

        $gallerysBanners = $em->getRepository ( 'BackBundle:Collectionmedia' )->findBy(array('type' => 'photos' , 'showPageBanniere' => 1));
        $personnageMediasBanners = $em->getRepository ( 'BackBundle:PersonnageMedia' )->findBy(array("showPageBanniere" => 1  ),array("id" => "DESC") );
     
        /** user non connecté */
        $alerteRendezvous = null;
        if ( $userCourant == null or  $userCourant->getShowVipBoxInHome() == 1 ) {
            $vipUsers = $em->getRepository ( 'UserBundle:User' )->findByFixeNumberShowInHome ( 'ROLE_VIP' , 15  );

            if( $vipUsers == null){
               $vipUsers = $em->getRepository ( 'UserBundle:User' )->findByFixeNumberVu ( 'ROLE_VIP' , 20 );
            }
           
            $allMediaTypes = $em->getRepository ( 'BackBundle:TypeMedia' )->getTousMediasListdate ( $language );

            $allMediaHtml = $this->renderView ( 'FrontBundle:Front:all_media.html.twig' ,
                array(
                    'listeTousTypeMedia' => $allMediaTypes ,
                    'crawlercinemas' => null ,
                    'crawlerradios' => null ,
                    'crawlerconcerts' => null ,
                    'crawlerspectacles' => null ,
                    'crawlernews' => null ,
                    'crawlerprogramme' => null ,
                ) );


        } /** user  connecté */
        else {

            /************** get Liste Vip *****************/
            /** liste de user vip in box **/
            $vip_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => 'user' , "enable" => 1 ) );

            /** liste de user vip in box egale 0  */
            if ( count ( $vip_In_Box ) == 0 ) {
              
                 $vipUsers = $em->getRepository ( 'UserBundle:User' )->findByFixeNumberShowInHome ( 'ROLE_VIP' , 15  );

            if( $vipUsers == null){
            $vipUsers = $em->getRepository ( 'UserBundle:User' )->findByFixeNumberVu ( 'ROLE_VIP' , 20 );
            }
        
            $viPouup = $em->getRepository ( 'UserBundle:User' )->findViPouup ( 'ROLE_VIP' , 6 , $userCourant->getId () );
              
            } /** liste de user vip in box >  0  **/
            else {
                $vipUsers = $em->getRepository ( 'UserBundle:User' )->getVipBox ( $userCourant->getId () , 1 );

   }

            /************** get Liste Media *****************/

            /** liste de Media in box **/
            $medias_In_Box = $em->getRepository ( 'BackBundle:Medias' )->getMediaBox ( $userCourant->getId () , 1 );

            /** liste de Media in box egale 0  */
            if ( count ( $medias_In_Box ) == 0 and count ( $vip_In_Box ) == 0 ) {
                $allMediaTypes = $em->getRepository ( 'BackBundle:TypeMedia' )->getTousMediasListdate ( $language );
                $allMediaHtml = $this->renderView ( 'FrontBundle:Front:all_media.html.twig' , array(
                    'listeTousTypeMedia' => $allMediaTypes ,
                    'crawlercinemas' => null ,
                    'crawlerradios' => null ,
                    'crawlerconcerts' => null ,
                    'crawlerspectacles' => null ,
                    'crawlernews' => null ,
                    'crawlerprogramme' => null ,

                ) );

            } /** liste de Media in box >  0  **/
            else {

                $cinema_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => 'cinema' , "enable" => 1 ) );
                $programme_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => 'programme' , "enable" => 1 ) );
                $concert_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => 'concert' , "enable" => 1 ) );
                $spectacle_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => 'spectacle' , "enable" => 1 ) );
                $radio_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => 'radio' , "enable" => 1 ) );
                $people_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => 'people' , "enable" => 1 ) );

                $allMediaHtml = $this->renderView ( 'FrontBundle:Front:all_media_box.html.twig' , array(
                    'cinemaInBox' => $cinema_In_Box ,
                    'programmeInBox' => $programme_In_Box ,
                    'concertInBox' => $concert_In_Box ,
                    'spectacleInBox' => $spectacle_In_Box ,
                    'radioInBox' => $radio_In_Box ,
                    'peopleInBox' => $people_In_Box ,
                    'userCourant' => $userCourant ,
                ) );

            }

            /**************  get Liste alerte box *****************/
            $alertBoxMedia = $em->getRepository ( 'BackBundle:Box' )->getMediaNextBox ( $userCourant->getId () , 1 );

        }

        $photos = null;
        $photosshowHome = null;
        $videos = null;
        $news = null;
        $alerteVip = null;
        if ( $vipUsers != null ) {

            $firstVipUser = $vipUsers[ 0 ];
            $photos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafix ( 4 , 'photos' , $firstVipUser->getId () );
            $photosshowHome = $em->getRepository('BackBundle:Collectionmedia')->findOneBy(array("showPageHome" => 1 , 'member' => $firstVipUser->getId () , "type" =>'photos' ))  ;

            $news = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafix ( 5 , 'news' , $firstVipUser->getId () );
            $alerteVip = $em->getRepository ( 'BackBundle:Medias' )->getMediasVip ( $firstVipUser->getId () );
            $alerteRendezvous = $em->getRepository ( 'BackBundle:Rendezvous' )->getAlertRDVVip ( $firstVipUser->getId () );

            $videos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafixVideos ( 6 , 'videos' , $firstVipUser->getId () );

        }

        /** Black Footer **/
        $information = $em->getRepository ( 'BackBundle:Information' )->findOneBy ( array() , array() , 1 );
        $footerHtml = $this->renderView ( 'FrontBundle:Template:footer.html.twig' , array( 'information' => $information , 'vipouup' => $viPouup ) );
        $mediasToDay = $em->getRepository ( 'BackBundle:Medias' )->getMediasCourant ();
        $listeMembre = $em->getRepository ( 'UserBundle:User' )->findByRole ( "ROLE_VIP" );
        $alerteRendezvousCourant = $em->getRepository ( 'BackBundle:Rendezvous' )->getAlertRDVVipCourant ();

        return $this->render ( 'FrontBundle:Front:home.html.twig' , array(
            'photosshowHome' => $photosshowHome ,
            'listeMembre' => $listeMembre ,
            'footerHtml' => $footerHtml ,
            'allMediaHtml' => $allMediaHtml ,
            'gallerys_index' => $gallerysIndexes ,
            'gallerys_banner' => $gallerysBanners ,
            'personnageMediasBanners' => $personnageMediasBanners,
            'ListePhotos' => $photos ,
            'ListeVideos' => $videos ,
            'ListeNews' => $news ,
            'alerteVip' => $alerteVip ,
            'mediasToDay' => $mediasToDay ,
            'userCourant' => $userCourant ,
            'ListeVIP' => $vipUsers ,
            'vipouup' => $viPouup ,
            'alertBoxMedia' => $alertBoxMedia ,
            'alerteRendezvous' => $alerteRendezvous ,
            'alerteRendezvousCourant' => $alerteRendezvousCourant ,


        ) );
    }

    /**
     * Image slider.
     *
     * @return Response A Response instance
     **/
    public function imagesliderAction ()
    {
        $em = $this->getDoctrine ()->getManager ();
        $galleryBanner = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomSingleEntity ( 'gallery_banner' );

        return $this->render ( 'FrontBundle:Template:image_slider.html.twig' , array(
            'galleryBanner' => $galleryBanner
        ) );
    }
    



/**
     * Add comment.
     *
     * @param Request $request The current request.
     * @param string $id The media identifier.
     *
     * @return Response A Response instance
     **/
    public function addCommentAction ( Request $request , $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $media = $em->getRepository ( 'BackBundle:Medias' )->find ( $id );

        $commenText = $request->get ( 'commentaire' );
        if ( $commenText != null ) {
            $newComment = new CommentaireMedia();
            $newComment->setDescription ( $commenText );
            $newComment->setMedia ( $media );
            $newComment->setUsers ( $this->getUser () );

            $em->persist ( $newComment );
            $em->flush ();

            $translator = $this->get ( 'translator' );
            $this->get ( 'session' )->getFlashBag ()->add ( 'commentairesuccess' , $translator->trans ( 'alert.add' ) );
        }

        return $this->redirectToRoute ( 'front_end_show_media' , array( 'id' => $id ) );
    }


    /********************************  Module  Media   *************************/


    /**
     * Media index page.
     *
     * @param String $type The type media .
     *
     * @return Response A Response instance
     **/
    public function mediaAction ( Request $request , $type )
    {
        $em = $this->getDoctrine ()->getManager ();
        $language = $request->getLocale ();

        $cinemas = null;
        $video_cinemas = null;
        $this->get ( 'session' )->set ( 'activerouteCourant' , $type );

        $next_medias = $em->getRepository ( 'BackBundle:Medias' )->getNextMedias ( $type , $language );

        /***  Module   Cinemas  ***/
        if ( $type == "cinema" ) {

            $cinemas = $em->getRepository ( 'BackBundle:Medias' )->getMediasBientotsalles ( $type , $language  );
            $cinemasActuellementsalles = $em->getRepository ( 'BackBundle:Medias' )->getMediasActuellementsalles ($type , $language  );
            $video_medias = $em->getRepository ( 'BackBundle:Medias' )->getMediasListvideo ( 6 , $type , $language );
            $crawlercinemas = $em->getRepository ( 'BackBundle:Crawlercinema' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( 'id' => 'DESC' ) , 5 );
       
          if( $video_medias == null ){
                $video_medias = $cinemasActuellementsalles ;
            }
           
            return $this->render ( 'FrontBundle:Cinema:index.html.twig' , array(
                "nextMedias" => $next_medias ,
                "videoCinemas" => $video_medias ,
                "cinemasActuellementsalles" => $cinemasActuellementsalles ,
                "cinemas" => $cinemas ,
                "crawlercinemas" => $crawlercinemas ,

            ) );
        }
        /***  End Module  Cinemas  ***/


        /***  Module Concerts    ***/
        if ( $type == "concert" ) {
             $old_events = $em->getRepository ( 'BackBundle:Medias' )->getMediasListNumbrefixe ( 8 , $type , $language );

            $video_concert = $em->getRepository ( 'BackBundle:Medias' )->getMediasListvideo ( 6 , $type , $language );
            $crawlerconcerts = $em->getRepository ( 'BackBundle:Crawlerconcert' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( 'id' => 'DESC' ) , 5 );

            return $this->render ( 'FrontBundle:Concert:index.html.twig' , array(
                "videoConcert" => $video_concert ,
                "nextMedias" => $next_medias ,
                "oldEvents" => $old_events ,
                "crawlerconcerts" => $crawlerconcerts ,

            ) );
        }
        /***  End Module  Concerts  ***/


        /***  Module Spectale    ***/
        if ( $type == "spectacle" ) {

            $crawlerspectacles = $em->getRepository ( 'BackBundle:Crawlerspectacle' )->findBy ( array( 'enable' => 1 ) , array( 'id' => 'DESC' ) , 5 );
            $liste_spectales = $em->getRepository ( 'BackBundle:Medias' )->getMediasListNumbrefixe ( 8 , $type , $language );
            $video_spectales = $em->getRepository ( 'BackBundle:Medias' )->getMediasListvideo ( 3 , $type , $language );

            return $this->render ( 'FrontBundle:Spectale:index.html.twig' , array(
                "videoSpectales" => $video_spectales ,
                "nextMedias" => $next_medias ,
                "listSpectales" => $liste_spectales ,
                "crawlerspectacles" => $crawlerspectacles ,

            ) );
        }
        /***  End Module  Spectale  ***/

        /***  Module Television    ***/
        if ( $type == "programme" ) {

            $liste_programme = $em->getRepository ( 'BackBundle:Medias' )->getMediasListNumbrefixe ( 8 , $type , $language );
            $crawlerprogramme = $em->getRepository ( 'BackBundle:Crawlerprogramme' )->findBy ( array( "enable" => 1 ) , array( 'id' => 'DESC' ) , 5 );
            $listeProgrammeVod = $em->getRepository ( 'BackBundle:Medias' )->getMediasProgrammeVod ($type );

            return $this->render ( 'FrontBundle:Television:index.html.twig' , array(
                "nextMedias" => $next_medias ,
                "listeProgramme" => $liste_programme ,
                "crawlerprogramme" => $crawlerprogramme ,
                "listeProgrammeVod" => $listeProgrammeVod ,

            ) );
        }
        /***  End Module  Television  ***/

        /***  Module People    ***/
        if ( $type == "people" ) {

            $crawlernews = $em->getRepository ( 'BackBundle:Crawlernews' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( 'id' => 'DESC' ) , 5 );

            $liste_fixe_people = $em->getRepository ( 'BackBundle:Medias' )->getTopPeople ( 3 , $type , $language );
            $liste_fixe_imgscreen_people = $em->getRepository ( 'BackBundle:Medias' )->getTopPeople ( 14 , $type , $language );

            $listeTopPeople = $em->getRepository ( 'BackBundle:Medias' )->getTopPeople( 6 , $type , $language );

            $tous_people = $em->getRepository ( 'BackBundle:Medias' )->getMediasListAll (  $type , $language );
            $video_people = $em->getRepository ( 'BackBundle:Medias' )->getMediasListvideo ( 4 , $type , $language );
            $tousPeoplescrollInfini = $em->getRepository ( 'BackBundle:Medias' )->getscrollInfini ( 8 , $type , $language );

            return $this->render ( 'FrontBundle:People:index.html.twig' , array(
                "nextMedias" => $next_medias ,
                "listeImgscreenPeople" => $liste_fixe_imgscreen_people ,
                "listePeople" => $liste_fixe_people ,
                "listeTopPeople" => $listeTopPeople ,
                "tousPeople" => $tous_people ,
                "videoPeople" => $video_people ,
                "crawlernews" => $crawlernews ,
                "tousPeoplescrollInfini" => $tousPeoplescrollInfini ,

            ) );
        }
        /***  End Module  Television  ***/

        /***  Module Radio    ***/
        if ( $type == "radio" ) {
            $liste_fixe_radio = $em->getRepository ( 'BackBundle:Medias' )->getMediasListNumbrefixe ( 3 , $type , $language );
             $chaine_radios = $em->getRepository ( 'BackBundle:Radio' )->findAll ();
            $crawlerradios = $em->getRepository ( 'BackBundle:Crawlerradio' )->findBy ( array( "enable" => 1 , "pays" => $language ) , array( 'id' => 'DESC' ) , 5 );


           $liste_radios = $em->getRepository ( 'BackBundle:Medias' )->getscrollInfini ( 8 , $type , $language );

            return $this->render ( 'FrontBundle:Radio:index.html.twig' , array(
                "nextMedias" => $next_medias ,
                "listeFixeRadio" => $liste_fixe_radio ,
                "listeRadios" => $liste_radios ,
                "chaineRadios" => $chaine_radios ,
                "crawlerradios" => $crawlerradios ,

            ) );
        }
        /***  End Module Radio  ***/

        return $this->render ( 'FrontBundle:Cinema:index.html.twig' , array(
            "videoCinemas" => $video_cinemas ,
            "cinemas" => $cinemas ,

        ) );
    }

    /**
     * @param $id
     * @return Response
     */
    public function profilPersonnageAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $personnage = $em->getRepository ( 'BackBundle:Personnage' )->find ( $id );
        return $this->render ( 'FrontBundle:Front:personnage.html.twig' , array(
            'personnage' => $personnage
        ) );
    }



    public function showmediaAction ( Request $request , $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $language = $request->getLocale ();
        $media = $em->getRepository ( 'BackBundle:Medias' )->find ( $id );
        $media->setNbrShow( $media->getNbrShow()  +  1) ;
        $em->merge($media);
        $em->flush();
        $Tweeteracteur = $em->getRepository ( 'BackBundle:Medias' )->getTweeteracteur ( $id );
        $Listeacteurs = $em->getRepository ( 'UserBundle:User' )->getListeacteur ( $id );
        $Listepersonnages = $em->getRepository ( 'BackBundle:Personnage' )->getListeacteur ( $id );
        $getTweeteracteur = $em->getRepository ( 'BackBundle:Personnage' )->getTweeteracteur ( $id );
        $Tweeteracteur = array_merge ( $Tweeteracteur , $getTweeteracteur );
        $user = $this->getUser ();
        $mediaInBox = null;
        $ListeNews = null;
        $listePhotos = null;
        $next_medias = null;



        if ( $user != null ){
            $mediaInBox = $em->getRepository ( 'BackBundle:Box' )->findOneBy ( array( "membre" => $user->getId () , "medias" => $media->getId () , "typebox" => $media->getTypemedia ()->getTitle () ) );

            $LikeUserMedia = $em->getRepository ( 'BackBundle:LikeMedia' )->findBy(array('media' => $media->getId() , 'member' => $this->getUser() ));
        }else{
            $LikeUserMedia = $em->getRepository ( 'BackBundle:LikeMedia' )->findBy(array('media' => $media->getId()  ));
        }

        if ( $media->getTypemedia () != null and  $media->getTypemedia ()->getTitle () == "cinema" ) {
            $munbre_listePhotos = 8;
            $media_Liste = $em->getRepository ( 'BackBundle:Medias' )->getMediasListvideo ( 4 , $media->getTypemedia ()->getTitle () , $language );
            $page = 'FrontBundle:Cinema:detail.html.twig';
        } else {
            if($media->getTypeContentMedia() != null ){
                $next_medias = $em->getRepository ( 'BackBundle:Medias' )->getMediasByTypeContent ( $id , $media->getTypeContentMedia()->getId() , $language , 15 );
            }
            if($next_medias == null ){
                $next_medias = $em->getRepository ( 'BackBundle:Medias' )->getNextMediasEvent ( $id , $media->getTypemedia ()->getTitle () , $language , 15 );
            }
            $media_Liste = $em->getRepository ( 'BackBundle:Medias' )->getMediasrand ( 6 , $language );
            $munbre_listePhotos = 5;
            $page = 'FrontBundle:Medias:detail.html.twig';
        }
        $listeIdActeur = $em->getRepository ( 'BackBundle:Medias' )->getIdacteur ( $id );
        $listePhotos = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomMedia ( $munbre_listePhotos , 'gallery_media' , $id );
        if (  $listeIdActeur != null ) {
            $ListeNews = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMediaListVipRand ( 3 , 'news' , $listeIdActeur );
            if (   $listePhotos  == null )
                $listePhotos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMediaListVipRand ( $munbre_listePhotos , 'photos' , $listeIdActeur );
        }

        $mediasToDay = $em->getRepository ( 'BackBundle:Medias' )->getMediasFutur ();
        $mediasToDayRDV = $em->getRepository ( 'BackBundle:Rendezvous' )->findgetRDVFuture ();


        return $this->render ( $page , array(
            "LikeUserMedia" => $LikeUserMedia ,
            "mediasToDayRDV" => $mediasToDayRDV ,
            "mediasToDay" => $mediasToDay ,
            "mediaInBox" => $mediaInBox ,
            "Tweeteracteur" => $Tweeteracteur ,
            "Listeacteurs" => $Listeacteurs ,
            "Listepersonnages" => $Listepersonnages ,
            "media" => $media ,
            "mediaListe" => $media_Liste ,
            "listeNews" => $ListeNews ,
            "listePhotos" => $listePhotos ,
            "nextMedias" => $next_medias


        ) );
    }


    public function showcrawlermediaAction ( Request $request , $id , $tyemedia )
    {
        $em = $this->getDoctrine ()->getManager ();
        $entity = null;
        $media = null;
        $Listemedia = null;
        switch ( $tyemedia ) {
            case 'cinema':
                $entity = 'BackBundle:Crawlercinema';
                break;
            case 'concert':
                $entity = 'BackBundle:Crawlerconcert';
                break;
            case "spectacle":
                $entity = 'BackBundle:Crawlerspectacle';
                break;
            case "radio":
                $entity = 'BackBundle:Crawlerradio';
                break;
            case "people":
                $entity = 'BackBundle:Crawlernews';
                break;
            case 'programme':
                $entity = 'BackBundle:Crawlerprogramme';
                break;
        }

        if ( $entity != null ) {
            $media = $em->getRepository ( $entity )->find ( $id );
            $Listemedia = $em->getRepository ( $entity )->findBy ( array( "enable" => 1 ) );
        }
        return $this->render ( 'FrontBundle:MediaCrawler:detail.html.twig' , array(
            "media" => $media ,
            "tyemedia" => $tyemedia ,
            "Listemedia" => $Listemedia ,

        ) );
    }


    /********************************  End Module  Media   *************************/
    /**
     * conditions Utilisation
     * @param  String $typeContent the type type content
     *
     * @return  Response A Reponse instance
     */
    public function aProposAction ( Request $request , $typeContent )
    {
        $em = $this->getDoctrine ()->getManager ();

        $this->get ( 'session' )->set ( 'activerouteCourant' , "propos" );
        $language = $request->getLocale ();
        $content = $em->getRepository ( 'BackBundle:Content' )->getContent ( $typeContent , $language );

        return $this->render ( 'FrontBundle:Apropos:index.html.twig' , array(
            'content' => $content ,
        ) );
    }
    /********************************  End Module  Media   *************************/
    /**
     * crossing Tv
     * @param  String $typeContent the type type content
     *
     * @return  Response A Reponse instance
     */
    public function crossingTvAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();

        $this->get ( 'session' )->set ( 'activerouteCourant' , "box" );
        /* $liste_Youtube = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneVideoYoutube (20, "videos"  , 'youtube' );
         $count = count($liste_mp4) + count($liste_Youtube) ;
         $videos = null ;
         $y = 0 ;
         $m = 0 ;
         $type = 'youtube' ;
         for($i = 1 ; $i < $count ; $i ++){
             if($type == 'youtube' and $i <= count($liste_Youtube) ){
             $videos[$i] =  $liste_Youtube[$y];
                 $type = 'mp4' ;
                 $y++;
             }elseif ($type == 'mp4' and $i <= count($liste_mp4)){
             $videos[$i] =  $liste_mp4[$m];
                 $m++;
                 $type = 'youtube' ;
             }else{
                 if ($type == 'youtube')
                     $type = 'mp4' ;
                 else
                     $type = 'youtube' ;
             }
         }
        */
        if ( $this->getUser () != null ) {

            /** liste de user vip in box **/
            $vip_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $this->getUser ()->getId () , "typebox" => 'user' , "enable" => 1 ) );

            /** liste de user vip in box egale 0  */
            if ( $vip_In_Box == null ) {
                $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );
                $videos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneByType ( 50 , "videos" , 'mp4' );

            } /** liste de user vip in box >  0  **/
            else {
                $vipUsers = $em->getRepository ( 'UserBundle:User' )->getVipBox ( $this->getUser ()->getId () , 1 );
                $gallerysBanners = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMediaListVipRand ( 15 , 'photos' , $vipUsers );
                $videos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneByTypeListeVip ( 50 , "videos" , 'mp4' , $vipUsers );

            }
        }

        if ( $this->getUser () == null ||  $gallerysBanners   == null ) {
            $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );
        }

        if ( $this->getUser () == null || $videos  == null ) {
            $videos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneByType ( 50 , "videos" , 'mp4' );
        }
        $listeVIP = $em->getRepository ( 'UserBundle:User' )->findByRoleRANDFixe ( "ROLE_VIP" , 5 );
     
        return $this->render ( 'FrontBundle:Front:crossingTv.html.twig' , array(
          'listeVIP' => $listeVIP,
            'gallerysBanners' => $gallerysBanners ,
            'videos' => $videos ,
        ) );
    }

    /**
     * crossing Tv
     * @param  String $typeContent the type type content
     *
     * @return  Response A Reponse instance
     */
    public function crossingAudioAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();

        $this->get ( 'session' )->set ( 'activerouteCourant' , "box" );

        if ( $this->getUser () != null ) {

            /** liste de user vip in box **/
            $vip_In_Box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $this->getUser ()->getId () , "typebox" => 'user' , "enable" => 1 ) );

            /** liste de user vip in box egale 0  */
            if ( $vip_In_Box  == null ) {
                $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );
                $audios = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneByType ( 50 , "videos" , 'mp3' );

            } /** liste de user vip in box >  0  **/
            else {
                $vipUsers = $em->getRepository ( 'UserBundle:User' )->getVipBox ( $this->getUser ()->getId () , 1 );
                $gallerysBanners = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMediaListVipRand ( 15 , 'photos' , $vipUsers );
                $audios = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneByTypeListeVip ( 50 , "videos" , 'mp3' , $vipUsers );

            }
        }

        if ( $this->getUser () == null ||  $gallerysBanners   == null ) {
            $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );
        }
        if ( $this->getUser () == null ||  $audios   == null ) {
            $audios = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneByType ( 50 , "videos" , 'mp3' );
        }


        return $this->render ( 'FrontBundle:Front:crossingAudio.html.twig' , array(
            'audios' => $audios ,
            'gallerysBanners' => $gallerysBanners ,
        ) );
    }

    /**
     * crossing Map
     * @param  String $typeContent the type type content
     *
     * @return  Response A Reponse instance
     */
    public function crossingMapAction ( Request $request  )
    {
        $em = $this->getDoctrine ()->getManager ();
        $search = $request->get ('searchWord');
        $User = null;

        if($search != null)
            $User = $em->getRepository ( 'UserBundle:User' )->searchUserAllMap ( $search  );

        $this->get ( 'session' )->set ( 'activerouteCourant' , "box" );
        $PictureCourantHumeur = null ;
        if( $search == null or $User == null ){
            $ListFanszenTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurAll ( "zen" );
            $ListFansgaiteTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurAll ( "gaite" );
            $ListFansdeprimeTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurAll ( "deprime" );
            $ListFanscolereTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurAll ( "colere" );
            $ListFansfatigueTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurAll ( "fatigue" );
            $ListFansenergiqueTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurAll ( "energique" );
            $ListFansTotal = $em->getRepository('UserBundle:User')->findByRole("ROLE_MEMBRE");
        }else{

            $ListFanszenTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurBox ( $User[0]->getId() , "zen" );
            $ListFansgaiteTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurBox (  $User[0]->getId()  , "gaite" );
            $ListFansdeprimeTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurBox (  $User[0]->getId()  , "deprime" );
            $ListFanscolereTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurBox (  $User[0]->getId()  , "colere" );
            $ListFansfatigueTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurBox (  $User[0]->getId()  , "fatigue" );
            $ListFansenergiqueTotal = $em->getRepository ( 'BackBundle:Box' )->getfansHumeurBox (  $User[0]->getId()  , "energique" );
            $ListFansTotal = $em->getRepository ( 'BackBundle:Box' )->getfansBox ( $User[0]->getId() );
            $PictureCourantHumeur = $em->getRepository ( 'BackBundle:Humeur' )->findOneBy ( array( "member" => $User[0]->getId() , "type" => $User[0]->getColoremotioncard()  ) );

        }
        $maxMode = max(count ( $ListFanszenTotal ) , count ( $ListFansgaiteTotal ) ,count ( $ListFansdeprimeTotal ) , count ( $ListFanscolereTotal ) , count ( $ListFansfatigueTotal ) , count ( $ListFansenergiqueTotal ) ) ;
        $arrayMode = array() ;
        $i = 0 ;
        $videos = null ;
        if($maxMode == count ( $ListFanszenTotal )  ){
            $arrayMode[$i] = "zen"; $i++;
        }
        if($maxMode == count ( $ListFansgaiteTotal )  ){
            $arrayMode[$i] = "gaite"; $i++;
        }
        if($maxMode == count ( $ListFansdeprimeTotal )  ){
            $arrayMode[$i] = "deprime"; $i++;
        }
        if($maxMode == count ( $ListFanscolereTotal )  ){
            $arrayMode[$i] = "colere"; $i++;
        }
        if($maxMode == count ( $ListFansfatigueTotal )  ){
            $arrayMode[$i] = "fatigue"; $i++;
        }
        if($maxMode == count ( $ListFansenergiqueTotal )  ){
            $arrayMode[$i] = "energique"; $i++;
        }
         if($arrayMode != null )
        $videos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneByMode (  "videos" , 'mp4', $arrayMode);

        /*** ajouter test sur le max et count et chaque fois on ajouter avec function php meger entre le variable courant et l'autre varibal ***/


        $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );
        $totlaTotal = count ( $ListFanszenTotal ) + count ( $ListFansgaiteTotal ) + count ( $ListFansdeprimeTotal ) + count ( $ListFanscolereTotal ) + count ( $ListFansfatigueTotal ) + count ( $ListFansenergiqueTotal );
        $listeVIP = $em->getRepository ( 'UserBundle:User' )->findByRoleRANDFixe ( "ROLE_VIP" , 5 );
     

        return $this->render ( 'FrontBundle:Front:crossingMap.html.twig' , array(
          //  'ListPictureHumeurTotal' => $ListPictureHumeurTotal ,
          'listeVIP' => $listeVIP,
          'search' => $search ,
            'videos' => $videos ,
            'user' => $User ,
            'ListFanszen' => $ListFanszenTotal ,
            'ListFansgaite' => $ListFansgaiteTotal ,
            'ListFansdeprime' => $ListFansdeprimeTotal ,
            'ListFanscolere' => $ListFanscolereTotal ,
            'ListFansfatigue' => $ListFansfatigueTotal ,
            'ListFansenergique' => $ListFansenergiqueTotal ,
            'totla' => $totlaTotal ,
            'ListFans' => $ListFansTotal ,
      //      'audios' => $audios ,
            'gallerysBanners' => $gallerysBanners ,
            'pictureCourantHumeur' => $PictureCourantHumeur ,
        ) );
    }


    /********************************   Module  research   *************************/
    /**
     * Page research
     * @param Request $request The current request.
     * @return Response A reponse instance
     */
    public function researchAction ( Request $request , $searchWord )
    {
        $em = $this->getDoctrine ()->getManager ();
        $gallery_banner = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomSingleEntity ( "gallery_banner" );
        $this->get ( 'session' )->set ( 'activerouteCourant' , "" );
        $ListeCinema = null;
        $ListeProgramme = null;
        $ListeConcert = null;
        $ListeSpectacle = null;
        $ListeRadio = null;
        $ListePeople = null;
        /*** Liste des class robots Crawler ***/

        $ListeCrawlerCinema = null;
        $ListeCrawlerProgramme = null;
        $ListeCrawlerConcert = null;
        $ListeCrawlerSpectacle = null;
        $ListeCrawlerRadio = null;
        $ListeCrawlerPeople = null;
        $listeMediaWebExterne = null;

        $language = $request->getLocale ();
        $ListeUser = null;
        $search = null;
        ( $searchWord == null ) ? $search = $request->request->get ( 'searchWord' ) : $search = $searchWord;

    
     $search =  trim($search );
   
     if ( $search != null or $search != "" ) {
            $userCourant = $this->getUser ();
            if ( $userCourant != null ) {
                $box = new Box();
                $box->setMembre ( $userCourant );
                $box->setWordsearch ( $search );
                $box->setTypebox ( 'wordsearch' );
                $em->persist ( $box );
                $em->flush ();
            }

            $ListeCinema = $em->getRepository ( 'BackBundle:Medias' )->getSearchWord ( $search , "cinema" , $language );
            $ListeProgramme = $em->getRepository ( 'BackBundle:Medias' )->getSearchWord ( $search , "programme" , $language );
            $ListeConcert = $em->getRepository ( 'BackBundle:Medias' )->getSearchWord ( $search , "concert" , $language );
            $ListeSpectacle = $em->getRepository ( 'BackBundle:Medias' )->getSearchWord ( $search , "spectacle" , $language );
            $ListeRadio = $em->getRepository ( 'BackBundle:Medias' )->getSearchWord ( $search , "radio" , $language );
            $ListePeople = $em->getRepository ( 'BackBundle:Medias' )->getSearchWord ( $search , "people" , $language );
           $ListeUser = $em->getRepository ( 'UserBundle:User' )->searchUserAll ( $search );

            /*** robots Crawler ***/
            $ListeCrawlerCinema = $em->getRepository ( 'BackBundle:Crawlercinema' )->getSearchWord ( $search , $language );
            $ListeCrawlerProgramme = $em->getRepository ( 'BackBundle:Crawlerprogramme' )->getSearchWord ( $search , $language );
            $ListeCrawlerConcert = $em->getRepository ( 'BackBundle:Crawlerconcert' )->getSearchWord ( $search , $language );
            $ListeCrawlerSpectacle = $em->getRepository ( 'BackBundle:Crawlerspectacle' )->getSearchWord ( $search , $language );
            $ListeCrawlerRadio = $em->getRepository ( 'BackBundle:Crawlerradio' )->getSearchWord ( $search , $language );
            $ListeCrawlerPeople = $em->getRepository ( 'BackBundle:Crawlernews' )->getSearchWord ( $search , $language );

            if ( $language == 'fr' )
                $listeMediaWebExterne = $this->rechercheWebSiteFR ( $search );
            else
                $listeMediaWebExterne = $this->rechercheWebSiteAN ( $search );

        }
        $photos = null;
        $photosshowHome = null;
        $videos = null;
        $news = null;
        $alerteVip = null;
        $gallerysVips = null;
        $alerteRendezvous = null;
        if (  $ListeUser != null and  count ( $ListeUser ) > 0 ) {

            $firstVipUser = $ListeUser[ 0 ];
            $photos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafix ( 4 , 'photos' , $firstVipUser->getId () );
            $photosshowHome = $em->getRepository('BackBundle:Collectionmedia')->findOneBy(array("showPageHome" => 1 , 'member' => $firstVipUser->getId () , "type" =>'photos' ))  ;
            $videos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafixVideos ( 3 , 'videos' , $firstVipUser->getId () );
            $news = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafix ( 5 , 'news' , $firstVipUser->getId () );
            $alerteVip = $em->getRepository ( 'BackBundle:Medias' )->getMediasVip ( $firstVipUser->getId () );
            $alerteRendezvous = $em->getRepository ( 'BackBundle:Rendezvous' )->getAlertRDVVip ( $firstVipUser->getId () );
       
        }

        $ListeUservip = $em->getRepository ( 'UserBundle:User' )->searchMembre ( $search , "ROLE_VIP" );
        if ( $ListeUservip != null and  count ( $ListeUservip ) > 0 )
            $gallerysVips = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMediaListVipRand ( 15 , 'photos' , $ListeUservip );
        else
            $gallerysVips = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMediaRand ( 15 , 'photos' );


        $mediasToDay = $em->getRepository ( 'BackBundle:Medias' )->getMediasCourant ();
        $alerteRendezvousCourant = $em->getRepository ( 'BackBundle:Rendezvous' )->getAlertRDVVipCourant ();


        return $this->render ( 'FrontBundle:Research:index.html.twig' , array(
            'alerteVip' => $alerteVip ,
            'mediasToDay' => $mediasToDay ,
            "ListeVideos" => $videos ,
            "ListeNews" => $news ,
            "ListePhotos" => $photos ,
            "photosshowHome" => $photosshowHome ,
            'gallerybanner' => $gallery_banner ,
            'ListeUser' => $ListeUser ,
            'ListeCinema' => $ListeCinema ,
            'ListeProgramme' => $ListeProgramme ,
            'ListeConcert' => $ListeConcert ,
            'ListeSpectacle' => $ListeSpectacle ,
            'ListeRadio' => $ListeRadio ,
            'ListePeople' => $ListePeople ,
            'ListeCrawlerCinema' => $ListeCrawlerCinema ,
            'ListeCrawlerProgramme' => $ListeCrawlerProgramme ,
            'ListeCrawlerConcert' => $ListeCrawlerConcert ,
            'ListeCrawlerSpectacle' => $ListeCrawlerSpectacle ,
            'ListeCrawlerRadio' => $ListeCrawlerRadio ,
            'ListeCrawlerPeople' => $ListeCrawlerPeople ,
            'searchWord' => $search ,
            'listeMediaWebExterne' => $listeMediaWebExterne ,
            'gallerys_banner' => $gallerysVips ,
            'alerteRendezvous' => $alerteRendezvous ,
            'alerteRendezvousCourant' => $alerteRendezvousCourant

        ) );
    }

    /********************************   Module  Contact   *************************/

    /**
     * Page Contact
     * @return Response A reponse instance
     */
    public function contactAction ()
    {
        $em = $this->getDoctrine ()->getManager ();
        $this->get ( 'session' )->set ( 'activerouteCourant' , "contact" );
        $gallery_banner = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomSingleEntity ( "gallery_banner" );
        $information = $em->getRepository ( 'BackBundle:Information' )->findOneBy ( array() , array() , 1 );
      
      
      
        return $this->render ( 'FrontBundle:Contact:index.html.twig' , array(
            'gallerybanner' => $gallery_banner ,
            'information' => $information ,
        ) );
    }
    /********************************  End Module  Contact   *************************/
    

    /**
     *  footer
     *
     * @return Response A reponse instance
     */
    public function referancementAction ($type)
    {
        $em = $this->getDoctrine ()->getManager ();
        $entity = $em->getRepository('BackBundle:Referancement')->findOneBy(array('typePage' => $type ));
      
        if($entity == null ){
            $entity = $em->getRepository('BackBundle:Referancement')->findOneBy(array('typePage' => "ref_home" ));
        }
      
        return $this->render ( 'FrontBundle:Template:referancement.html.twig' , array(
            'entity' => $entity , 
        ) );
    }

   /**
     *  footer
     *
     * @return Response A reponse instance
     */
    public function footerAction ()
    {
        $em = $this->getDoctrine ()->getManager ();
        $information = $em->getRepository ( 'BackBundle:Information' )->findOneBy ( array() , array() , 1 );

        return $this->render ( 'FrontBundle:Template:footer.html.twig' , array(
            'information' => $information ,
            'vipouup' => null ,
        ) );
    }

    /**
     *  menu
     * @param string
     * @return Response A reponse instance
     */
    public function menuAction ( $rout )
    {
        $em = $this->getDoctrine ()->getManager ();
        $userCourant = $this->getUser ();
        $ListeNews = null;
        if ( $userCourant == null ) {
            $ListeNews = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMedia (4 , 'news' );
        } else {
            $ListVip = $em->getRepository ( 'BackBundle:Box' )->getVipBox ( $userCourant->getId () );
            $ListeNews = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMediaListVip ( 4 , 'news' , $ListVip );
        }
        $i = 1 ;
        $listeDescription = [] ;
        foreach ($ListeNews as $value){



                $listeDescription[$i] =strip_tags( $value->getDescription());


            $i = $i +1 ;


        }


        return $this->render ( 'FrontBundle:Template:menu.html.twig' , array(
            "ListeNews" => $ListeNews ,
            "listeDescription" => $listeDescription ,
            "rout" => $rout ,
        ) );
    }

    private function rechercheWebSiteFR ( $term )
    {

        $array_resultat = array();
        $i = 0;
        // will content all the archetypes
        $list = array();

        //http://www.parismatch.com  //ok
        //  $parismatchcom = new Archetype( 'http://www.parismatch.com/recherche/recherche-globale?search=' , "//section[@id='main']/div[@class='container']/div[@class='row']/div[@class='col-md-12 aside-container']/div[@class='row list']/div[@class='list listing-rub']/div[@class='row ']" , "div[@class='col-md-6 col-sm-8 col-xs-12']/h2/a" , "div[@class='col-md-6 col-sm-8 col-xs-12']/h2/a/@href" , "div[@class='col-md-6 col-sm-4 col-xs-12']/a/img/@src" , "div[@class='col-md-6 col-sm-8 col-xs-12']/p" , "div[@class='col-md-6 col-sm-8 col-xs-12']/small" );


        //http://www.non-stop-people.com //ok
        $nonstoppeoplecom = new Archetype( 'http://www.non-stop-people.com/search/node/' , "//div[@class='listing']/ul[@class='list node-results']/li[@class='list-sep__item']" , "a[@class='feat__title']" , "div[@class='mediao__body']/a/@href" , "div[@class='mediao__figure']/div[@class='feat__visuel']/img/@src" , "div[@class='mediao__body']/div[@class='feat__text']" , "div[@class='mediao__body']/div[@class='feat__date']" );


        //https://www.telestar.fr/ //picture problems
      //  $telestarfr = new Archetype( 'https://www.telestar.fr/searchadvanced/multiple?SearchText=' , "//div[@class='teaser-x-items display_1111_2cols_48   ']/div[@class='row']/div[@class='item col-xs-12 ']/div[@class='row']" , "div[@class='infos col-xs-7 col-sm-8']/div/div[@class='title']/a" , "div[@class='infos col-xs-7 col-sm-8']/div/div[@class='title']/a/@href" , "div[@class='image col-xs-5 col-sm-4']/span[@class='sculturL']/noscript/img[@class='lazy']/@src" , "div[@class='infos col-xs-7 col-sm-8']/div/span[@class='chapo ']" , "div[@class='infos col-xs-7 col-sm-8']/div/span[@class='meta']/span/time" );


        //http://www.elle.fr/ //ok
        $ellefr = new Archetype( 'http://www.elle.fr/recherche/recherche-globale?searchText=' , "//div[@class='searchResults col-md-12 aside-container']/div[@class='resultItem clearfix']" , "div[@class='resultItem-text col-xs-12 col-md-9']/strong/a" , "div[@class='resultItem-text col-xs-12 col-md-9']/strong/a/@href" , "div[@class='resultItem-img col-xs-12 col-md-3']/span[@class='_NOL']/img/@src" , "div[@class='resultItem-text col-xs-12 col-md-9']/p[@class='resultItem-desc']" , "div[@class='resultItem-text col-xs-12 col-md-9']/p[@class='resultItem-meta']/span[@class='resultItem-date']" );

        //https://www.public.fr  //ok
        // $publicfr = new Archetype( 'https://www.public.fr/recherche/simple?search_form[sortBy]=published&search_form[text]=' , "//div[@class='News News--last media js-News']" , "a[@class='News-title']" , "a[@class='News-title']/@href" , "a[@class='News-imgCont media-left']/picture/source[1]/@data-srcset" , "a[@class='News-title']" , "p[@class='News-label label label--cold']" );


        //  array_push ( $list , $publicfr );
        //  array_push ( $list , $parismatchcom );
        array_push ( $list , $nonstoppeoplecom );
       // array_push ( $list , $telestarfr );
        array_push ( $list , $ellefr );

        if ( $list != null ) {
            foreach ( $list as $site ) {
                
                if ( isset( $term ) && ! empty( $term ) ) {


                    //header('Content-type: text/plain');
                    $html = file_get_contents ( $site->getWebsite () . '' . rawurlencode ( $term ) );

                    $pieces = parse_url ( $site->getWebsite () );
                    $domain = isset( $pieces[ 'host' ] ) ? $pieces[ 'host' ] : '';
                    if ( preg_match ( '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i' , $domain , $regs ) ) {
                        $path = strstr ( $regs[ 'domain' ] , '.' , true );
                    }

                    // echo htmlentities($html);
                    $content = new \DomDocument();

                    //disable libxml errors
                    libxml_use_internal_errors ( TRUE );

                    //parse_url($url, PHP_URL_HOST)
                    if ( ! empty( $html ) ) {

                        $content->loadHTML ( '<?xml encoding="utf-8" ?>' . $html );
                        //remove errors for yucky html
                        libxml_clear_errors ();
                        $xpath = new \DOMXPath( $content );

                        $nodes = $xpath->query ( $site->getContainerXpath () );

                        if ( $nodes->length > 0 ) {
                            foreach ( $nodes as $node ) {

                                $titleNode = $xpath->query ( "descendant::" . $site->getTitleClass () , $node )->item ( 0 );
                                $articleNode = $xpath->query ( "descendant::" . $site->getArticleLink () , $node )->item ( 0 );
                                $imageNode = $xpath->query ( "descendant::" . $site->getImageClass () , $node )->item ( 0 );
                                $descriptionNode = $xpath->query ( "descendant::" . $site->getDescriptionClass () , $node )->item ( 0 );
                                $timeNode = $xpath->query ( "descendant::" . $site->getTimeClass () , $node )->item ( 0 );
                                if ( $articleNode != null ) {
                                    if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.non-stop-people.com" )
                                        $array_resultat[ $i ][ 'lien' ] = 'http://www.non-stop-people.com' . $articleNode->nodeValue;
                                    else if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.telestar.fr" )
                                        $array_resultat[ $i ][ 'lien' ] = "https://www.telestar.fr" . $articleNode->nodeValue;
                                    else if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.elle.fr" )
                                        $array_resultat[ $i ][ 'lien' ] = "http://www.elle.fr" . $articleNode->nodeValue;
                                    /*     else if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.public.fr" )
                                             $array_resultat[ $i ][ 'lien' ] = $articleNode->nodeValue;
                                    */ else if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.parismatch.com" )
                                        $array_resultat[ $i ][ 'lien' ] = trim ( $articleNode->nodeValue );
                                    else
                                        $array_resultat[ $i ][ 'lien' ] = trim ( $articleNode->nodeValue );

                                    $array_resultat[ $i ][ 'title' ] = trim ( $titleNode->nodeValue );

                                    if ( $imageNode == null )
                                        $array_resultat[ $i ][ 'image' ] = "";
                                    else
                                        $array_resultat[ $i ][ 'image' ] = $imageNode->nodeValue;

                                    if ( $descriptionNode != null )
                                        $array_resultat[ $i ][ 'description' ] = trim ( $descriptionNode->nodeValue );
                                    else
                                        $array_resultat[ $i ][ 'description' ] = "";

                                    if ( $timeNode != null )
                                        $array_resultat[ $i ][ 'time' ] = trim ( $timeNode->nodeValue );
                                    else
                                        $array_resultat[ $i ][ 'time' ] = "";
                                    if ( $path != null )
                                        $array_resultat[ $i ][ 'path' ] = trim ( $path );
                                    else
                                        $array_resultat[ $i ][ 'path' ] = "";
                                }


                                $i = $i + 1;

                            }
                        }
                    }
                }
            }
        }
        return $array_resultat;
    }


    private function rechercheWebSiteAN ( $term )
    {

        $array_resultat = array();
        $i = 0;
        // will content all the archetypes
        $list = array();

//http://vipmagazine.ie/ //date
        $vipmagazineir = new Archetype( 'http://vipmagazine.ie/?s=' , "//div[@class='content container clear']/main[@class='main']/div[@class='latest-articles-wrapper clear']/article[contains(@class, 'post')]" , "h3" , "a/@href" , "div[@class='article-thumb']/img/@src" , "div[@class='tpn']/div[@class='click_wrapper _NOL']/p" , "time" );

        array_push ( $list , $vipmagazineir );

        if ( $list != null ) {
            foreach ( $list as $site ) {

                if ( isset( $term ) && ! empty( $term ) ) {


                    //header('Content-type: text/plain');
                    $html = file_get_contents ( $site->getWebsite () . '' . rawurlencode ( $term ) );

                    $pieces = parse_url ( $site->getWebsite () );
                    $domain = isset( $pieces[ 'host' ] ) ? $pieces[ 'host' ] : '';
                    if ( preg_match ( '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i' , $domain , $regs ) ) {
                        $path = strstr ( $regs[ 'domain' ] , '.' , true );
                    }

                    // echo htmlentities($html);
                    $content = new \DomDocument();

                    //disable libxml errors
                    libxml_use_internal_errors ( TRUE );

                    //parse_url($url, PHP_URL_HOST)
                    if ( ! empty( $html ) ) {

                        $content->loadHTML ( '<?xml encoding="utf-8" ?>' . $html );
                        //remove errors for yucky html
                        libxml_clear_errors ();
                        $xpath = new \DOMXPath( $content );

                        $nodes = $xpath->query ( $site->getContainerXpath () );

                        if ( $nodes->length > 0 ) {
                            foreach ( $nodes as $node ) {

                                $titleNode = $xpath->query ( "descendant::" . $site->getTitleClass () , $node )->item ( 0 );
                                $articleNode = $xpath->query ( "descendant::" . $site->getArticleLink () , $node )->item ( 0 );
                                $imageNode = $xpath->query ( "descendant::" . $site->getImageClass () , $node )->item ( 0 );
                                $descriptionNode = $xpath->query ( "descendant::" . $site->getDescriptionClass () , $node )->item ( 0 );
                                $timeNode = $xpath->query ( "descendant::" . $site->getTimeClass () , $node )->item ( 0 );
                                if ( $articleNode != null ) {
                                    if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.non-stop-people.com" )
                                        $array_resultat[ $i ][ 'lien' ] = 'http://www.non-stop-people.com' . $articleNode->nodeValue;
                                    else if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.telestar.fr" )
                                        $array_resultat[ $i ][ 'lien' ] = "https://www.telestar.fr" . $articleNode->nodeValue;
                                    else if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.elle.fr" )
                                        $array_resultat[ $i ][ 'lien' ] = "http://www.elle.fr" . $articleNode->nodeValue;
                                    /*     else if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.public.fr" )
                                             $array_resultat[ $i ][ 'lien' ] = $articleNode->nodeValue;
                                    */ else if ( parse_url ( $site->getWebsite () , PHP_URL_HOST ) == "www.parismatch.com" )
                                        $array_resultat[ $i ][ 'lien' ] = $articleNode->nodeValue;
                                    else
                                        $array_resultat[ $i ][ 'lien' ] = trim ( $articleNode->nodeValue );

                                    $array_resultat[ $i ][ 'title' ] = trim ( $titleNode->nodeValue );

                                    if ( $imageNode == null )
                                        $array_resultat[ $i ][ 'image' ] = "";
                                    else
                                        $array_resultat[ $i ][ 'image' ] = trim ( $imageNode->nodeValue );

                                    if ( $descriptionNode != null )
                                        $array_resultat[ $i ][ 'description' ] = trim ( $descriptionNode->nodeValue );
                                    else
                                        $array_resultat[ $i ][ 'description' ] = "";

                                    if ( $timeNode != null )
                                        $array_resultat[ $i ][ 'time' ] = trim ( $timeNode->nodeValue );
                                    else
                                        $array_resultat[ $i ][ 'time' ] = "";
                                    if ( $path != null )
                                        $array_resultat[ $i ][ 'path' ] = trim ( $path );
                                    else
                                        $array_resultat[ $i ][ 'path' ] = "";
                                }


                                $i = $i + 1;

                            }
                        }
                    }
                }
            }
        }
        return $array_resultat;
    }


    public function array_group_by ( array $array , $key )
    {
        if ( ! is_string ( $key ) && ! is_int ( $key ) && ! is_float ( $key ) && ! is_callable ( $key ) ) {
            trigger_error ( 'array_group_by(): The key should be a string, an integer, or a callback' , E_USER_ERROR );
            return null;
        }
        $func = ( is_callable ( $key ) ? $key : null );
        $_key = $key;
        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ( $array as $value ) {
            if ( is_callable ( $func ) ) {
                $key = call_user_func ( $func , $value );
            } elseif ( is_object ( $value ) && isset( $value->{$_key} ) ) {
                $key = $value->{$_key};
            } elseif ( isset( $value[ $_key ] ) ) {
                $key = $value[ $_key ];
            } else {
                continue;
            }
            $grouped[ $key ][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if ( func_num_args () > 2 ) {
            $args = func_get_args ();
            foreach ( $grouped as $key => $value ) {
                $params = array_merge ( [ $value ] , array_slice ( $args , 2 , func_num_args () ) );
                $grouped[ $key ] = call_user_func_array ( 'array_group_by' , $params );
            }
        }
        return $grouped;
    }


    public function proxyAction ( Request $request )
    {

        // Set a proper JSON header
        header ( 'Content-type: application/json' );

        /*----------------------------
            Twitter API settings
        -----------------------------*/

        // Consumer key
        $twitter_consumer_key = 'OxHYWuYIrxwPwcO6nraaxBBhs';

        // Consumer secret. Don't share it with anybody!
        $twitter_consumer_secret = 'UHwKU7LsZKciRCnAkLh5nzvRfuBLoDb0HHM7PPq6cqRSkyQjRd';

        // Access token
        $twitter_access_token = '874690555107844096-NKccPYgH39B2o8CX39dYaKcEJgiufvD';

        // Access token secrent. Also don't share it!
        $twitter_token_secret = '3pXZA0kXgD4Biw1aOM1TXjbfEKG8SgPRu4QfB3R1cmvKE';

        /*----------------------------
            Initialize codebird
        -----------------------------*/

        // Application settings
        $this->get ( 'app.codebird' )->setConsumerKey ( $twitter_consumer_key , $twitter_consumer_secret );

        $cb = $this->get ( 'app.codebird' )->getInstance ();

        // Your account settings
        $cb->setToken ( $twitter_access_token , $twitter_token_secret );

        /*----------------------------
            Handle the API request
        -----------------------------*/

// Is the handle array passed?
        if ( ! isset( $_POST[ 'handles' ] ) || ! is_array ( $_POST[ 'handles' ] ) ) {
            exit;
        }


// Does a cache exist?

        $cache = md5 ( implode ( $_POST[ 'handles' ] ) ) . '.cache';

        if ( file_exists ( $cache ) && time () - filemtime ( $cache ) < 15 * 60 ) {
            // There is a cache file, and it is fresher than 15 minutes. Use it!

            echo file_get_contents ( $cache );
            exit;
        }

// There is no cache file. Build it!

        $twitter_names = array();

        foreach ( $_POST[ 'handles' ] as $handle ) {

            if ( ! preg_match ( '/^[a-z0-9\_]+$/i' , $handle ) ) {
                // This twitter name is not valid. Skip it.
                continue;
            }

            $twitter_names[] = 'from:' . $handle;
        }

        $search_string = implode ( ' OR ' , $twitter_names );

// Issue a request for the Twitter search API using the codebird library
        $reply = (array) $cb->search_tweets ( "q=$search_string&count=50" );
        $result = json_encode ( $reply );

// Create/update the cache file
        file_put_contents ( $cache , $result );

// Print the result
        echo $result;
    }


}
