<?php

namespace MagazineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MagazineController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** Page Home **/
        $CoverImage = $em->getRepository('BackBundle:Magazinegallery')->findOneBy(array("state" => 1 , "typegallerys" => "admin"));
        $Liste_VIP = $em->getRepository ( 'UserBundle:User' )->findByRoleRANDFixe ( "ROLE_VIP" , 5 );
        $language = $request->getLocale();
        $list_concert = $em->getRepository('BackBundle:Medias')->getMediasMagazine ( 3 ,"concert" , $language );
        $list_cinema = $em->getRepository('BackBundle:Medias')->getMediasMagazine ( 3 , "cinema" , $language );
        $list_spectacle = $em->getRepository('BackBundle:Medias')->getMediasMagazine ( 3 , "spectacle" , $language );
        $list_programme = $em->getRepository('BackBundle:Medias')->getMediasMagazine ( 3 , "programme" , $language );


        /** page contient tous les programmes **/
        $listProgrammes = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "programme" , $language );
        $listConcerts = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "concert" , $language );
        $listCinemas = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "cinema" , $language );
        $listSpectacles = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "spectacle" , $language );
        $listRadios = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "radio" , $language );
        $listPeoples = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "people" , $language );
        $crosswords = $em->getRepository ( 'BackBundle:Crossword' )->findAll ();
        $magazine_dir = $this->getParameter ( 'magazine_dir' );

        $json = file_get_contents ( "" . $magazine_dir . "dev/magzine/data.json" );
        $parsed_json = json_decode ( $json , true );

        $resultatwords = array();
        for ( $i = 0 ; $i < count ( $parsed_json[ 'acrossClues' ] ) ; $i++ ) {
            $resultatwords[ $i ] = $parsed_json[ 'acrossClues' ][ $i ][ 'answer' ];
        }
        $k = count ( $parsed_json[ 'acrossClues' ] );
        for ( $i = 0 ; $i < count ( $parsed_json[ 'downClues' ] ) ; $i++ ) {
            $k++;
            $resultatwords[ $k ] = $parsed_json[ 'downClues' ][ $i ][ 'answer' ];
        }

        $rendezvous = $em->getRepository ( 'BackBundle:Rendezvous' )->findgetRDV ();

        $vipUsers = $em->getRepository ( 'UserBundle:User' )->findViPMagazine ( 'ROLE_VIP' , 1 );
        $firstVipUser = $vipUsers[ 0 ];
        $firstVipUserNews = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafix ( 1 , 'news' , $firstVipUser->getId () );
        $listeNews = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMedia ( 12 , 'news' );
        $NewsSinglePagetwo = null;
        $lastNews = null;
        $NewsPageOne = array();
        $NewsPagetwo = array();
        $NewsPagequatro = array();

        if ( $listeNews != null ) {

            for ( $i = 0 ; $i < count ( $listeNews ) ; $i++ ) {
                if ( $i == 0 )
                    $lastNews = $listeNews[ $i ];
                if($i > 0 and  $i < 4)
                    $NewsPageOne[ $i ] = $listeNews[ $i ];

                if( $i == 4)
                    $NewsSinglePagetwo = $listeNews[ $i ];
             
                if($i > 4 and  $i < 7)
                    $NewsPagetwo[ $i ] = $listeNews[ $i ];
                if($i > 6 and  $i < 12)
                    $NewsPagequatro[ $i ] = $listeNews[ $i ];
            }
        }

        $vipUsers = $em->getRepository ( 'UserBundle:User' )->findByFixeNumberVu ( 'ROLE_VIP' , 10 );


        return $this->render('MagazineBundle:Magazine:index.html.twig',array(
            "listConcert" => $list_concert ,
            "listCinema" => $list_cinema ,
            "listSpectacle" => $list_spectacle ,
            "listProgramme" => $list_programme ,

            'listProgrammes' => $listProgrammes ,
            'listCinemas' => $listCinemas ,
            'listSpectacles' => $listSpectacles ,
            'listRadios' => $listRadios ,
            'listPeoples' => $listPeoples ,
            'listConcerts' => $listConcerts ,

            'firstVipUser' => $firstVipUser ,
            'firstVipUserNews' => $firstVipUserNews ,

            'lastNews' => $lastNews ,
            'NewsPageOne' => $NewsPageOne ,
            'NewsSinglePagetwo' => $NewsSinglePagetwo ,
            'NewsPagetwo' => $NewsPagetwo ,
            'NewsPagequatro' => $NewsPagequatro ,
            
            'vipUsers' => $vipUsers ,


            'crosswords' => $crosswords ,
            'resultatwords' => $resultatwords ,

            'rendezvous' => $rendezvous ,


            "ListeVIP" => $Liste_VIP ,
            "coverImage" => $CoverImage ,
        ));
    }

    public function getjsonAction ( )
    {
        $em = $this->getDoctrine ()->getManager ();
        $userCourant = $this->getUser ();
        $Cinema_in_box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => "cinema" , 'enable' => 1 ) );
        $Spectacle_in_box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => "spectacle" , 'enable' => 1 ) );
        $Radio_in_box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => "radio" , 'enable' => 1 ) );
        $Concert_in_box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => "concert" , 'enable' => 1 ) );
        $Programme_in_box = $em->getRepository ( 'BackBundle:Box' )->findBy ( array( "membre" => $userCourant->getId () , "typebox" => "programme" , 'enable' => 1 ) );
        $pathImage = $this->getParameter('upload_Url');

        $events = array();
        if ( $Cinema_in_box != null ) {
            foreach ( $Cinema_in_box as $value ) {
                $event = new  \stdClass();
                $event->name = $value->getMedias ()->getTitle ();

                $event->image = $pathImage . $value->getMedias ()->getPicture ();

                $event->day = $value->getMedias ()->getStartdate ()->format ( 'd' );
                $event->month = $value->getMedias ()->getStartdate ()->format ( 'm' );
                $event->year = $value->getMedias ()->getStartdate ()->format ( 'Y' );
                $event->duration =  $value->getEndDate ()->format ( 'Ymd' ) - $value->getStartDate ()->format ( 'Ymd' );

                $event->time = $value->getMedias ()->getStarttime ();
                $date = date ( "Ymd" );
                if ( $date == $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "3";
                } elseif ( $date < $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "1";
                } else {
                    $event->color = "4";
                }
                $event->location = $value->getMedias ()->getChannel ();
                $event->description = utf8_encode ( $value->getMedias ()->getShortdescription () );
                array_push ( $events , $event );
            }
        }
        if ( $Spectacle_in_box != null ) {
            foreach ( $Spectacle_in_box as $value ) {
                $event = new  \stdClass();
                $event->name = $value->getMedias ()->getTitle ();
                $event->image = $pathImage . $value->getMedias ()->getPicture ();

                $event->day = $value->getMedias ()->getStartdate ()->format ( 'd' );
                $event->month = $value->getMedias ()->getStartdate ()->format ( 'm' );
                $event->year = $value->getMedias ()->getStartdate ()->format ( 'Y' );
               $event->duration =  $value->getEndDate ()->format ( 'Ymd' ) - $value->getStartDate ()->format ( 'Ymd' );

                $event->time = $value->getMedias ()->getStarttime ();
                $date = date ( "Ymd" );
                if ( $date == $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "3";
                } elseif ( $date < $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "1";
                } else {
                    $event->color = "4";
                }
                $event->location = $value->getMedias ()->getChannel ();
                $event->description = utf8_encode ( $value->getMedias ()->getShortdescription () );
                array_push ( $events , $event );
            }
        }
        if ( $Radio_in_box != null ) {
            foreach ( $Radio_in_box as $value ) {
                $event = new  \stdClass();
                $event->name = $value->getMedias ()->getTitle ();
                $event->image = $pathImage . $value->getMedias ()->getPicture ();

                $event->day = $value->getMedias ()->getStartdate ()->format ( 'd' );
                $event->month = $value->getMedias ()->getStartdate ()->format ( 'm' );
                $event->year = $value->getMedias ()->getStartdate ()->format ( 'Y' );
                $event->duration =  $value->getEndDate ()->format ( 'Ymd' ) - $value->getStartDate ()->format ( 'Ymd' );

                $event->time = $value->getMedias ()->getStarttime ();
                $date = date ( "Ymd" );
                if ( $date == $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "3";
                } elseif ( $date < $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "1";
                } else {
                    $event->color = "4";
                }
                $event->location = $value->getMedias ()->getChannel ();
                $event->description = utf8_encode ( $value->getMedias ()->getShortdescription () );
                array_push ( $events , $event );
            }
        }
        if ( $Concert_in_box != null ) {
            foreach ( $Concert_in_box as $value ) {
                $event = new  \stdClass();
                $event->name = $value->getMedias ()->getTitle ();
                $event->image = $pathImage . $value->getMedias ()->getPicture ();

                $event->day = $value->getMedias ()->getStartdate ()->format ( 'd' );
                $event->month = $value->getMedias ()->getStartdate ()->format ( 'm' );
                $event->year = $value->getMedias ()->getStartdate ()->format ( 'Y' );
                $event->duration =  $value->getEndDate ()->format ( 'Ymd' ) - $value->getStartDate ()->format ( 'Ymd' );

                $event->time = $value->getMedias ()->getStarttime ();
                $date = date ( "Ymd" );
                if ( $date == $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "3";
                } elseif ( $date < $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "1";
                } else {
                    $event->color = "4";
                }
                $event->location = $value->getMedias ()->getChannel ();
                $event->description = utf8_encode ( $value->getMedias ()->getShortdescription () );
                array_push ( $events , $event );
            }
        }
        if ( $Programme_in_box != null ) {
            foreach ( $Programme_in_box as $value ) {
                $event = new  \stdClass();
                $event->name = $value->getMedias ()->getTitle ();
                $event->image = $pathImage . $value->getMedias ()->getPicture ();

                $event->day = $value->getMedias ()->getStartdate ()->format ( 'd' );
                $event->month = $value->getMedias ()->getStartdate ()->format ( 'm' );
                $event->year = $value->getMedias ()->getStartdate ()->format ( 'Y' );
                $event->duration =  $value->getEndDate ()->format ( 'Ymd' ) - $value->getStartDate ()->format ( 'Ymd' );

                $event->time = $value->getMedias ()->getStarttime ();
                $date = date ( "Ymd" );
                if ( $date == $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "3";
                } elseif ( $date < $value->getMedias ()->getStartdate ()->format ( 'Ymd' ) ) {
                    $event->color = "1";
                } else {
                    $event->color = "4";
                }
                $event->location = $value->getMedias ()->getChannel ();
                $event->description = utf8_encode ( $value->getMedias ()->getShortdescription () );
                array_push ( $events , $event );
            }
        }
        echo json_encode ( $events );
        exit();
 
    }

    public function magazinememberAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $userCourant = $this->getUser ();
        $Liste_id_VIP = $em->getRepository ( 'BackBundle:Box' )->getVipBoxRAND ( $userCourant->getId () );
        if ( count ( $Liste_id_VIP ) > 5 ) {
            $Liste_VIP = $em->getRepository ( 'UserBundle:User' )->getListFixVIP ( $Liste_id_VIP );

            $rendezvous = $em->getRepository ( 'BackBundle:Rendezvous' )->findListvipgetRDV ($Liste_id_VIP);

            /** Page Home **/
            $CoverImage = $em->getRepository ( 'BackBundle:Magazinegallery' )->getGalleryListVip ( $Liste_id_VIP );
            /* $firstPhoto = (isset($photos[0])) ? $photos[0]:null;*/

            $firstCoverImage = (count($CoverImage) > 0 )? $CoverImage[0]: $em->getRepository('BackBundle:Magazinegallery')->findOneBy(array("state" => 1 , "typegallerys" => "admin"));

            $language = $request->getLocale ();
            $list_concert = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 3 , "concert" , $language );
            $list_cinema = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 3 , "cinema" , $language );
            $list_spectacle = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 3 , "spectacle" , $language );
            $list_programme = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 3 , "programme" , $language );


            /** page contient tous les programmes **/
            $listProgrammes = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "programme" , $language );
            $listConcerts = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "concert" , $language );
            $listCinemas = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "cinema" , $language );
            $listSpectacles = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "spectacle" , $language );
            $listRadios = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "radio" , $language );
            $listPeoples = $em->getRepository ( 'BackBundle:Medias' )->getMediasMagazine ( 12 , "people" , $language );
            $crosswords = $em->getRepository ( 'BackBundle:Crossword' )->findAll ();
            $magazine_dir = $this->getParameter ( 'magazine_dir' );

            $json = file_get_contents ( "" . $magazine_dir . "dev/magzine/data.json" );
            $parsed_json = json_decode ( $json , true );

            $resultatwords = array();
            for ( $i = 0 ; $i < count ( $parsed_json[ 'acrossClues' ] ) ; $i++ ) {
                $resultatwords[ $i ] = $parsed_json[ 'acrossClues' ][ $i ][ 'answer' ];
            }
            $k = count ( $parsed_json[ 'acrossClues' ] );
            for ( $i = 0 ; $i < count ( $parsed_json[ 'downClues' ] ) ; $i++ ) {
                $k++;
                $resultatwords[ $k ] = $parsed_json[ 'downClues' ][ $i ][ 'answer' ];
            }

          
            $firstVipUser = $Liste_VIP[ 0 ];
            $firstVipUserNews = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafix ( 1 , 'news' , $firstVipUser->getId () );
            $listeNews = $em->getRepository ( 'BackBundle:Collectionmedia' )->getMedia ( 12 , 'news' );
            $NewsSinglePagetwo = null;
            $lastNews = null;
            $NewsPageOne = array();
            $NewsPagetwo = array();
            $NewsPagequatro = array();

            if ( $listeNews != null ) {

                for ( $i = 0 ; $i < count ( $listeNews ) ; $i++ ) {
                    if ( $i == 0 )
                        $lastNews = $listeNews[ $i ];
                    if ( $i > 0 and $i < 4 )
                        $NewsPageOne[ $i ] = $listeNews[ $i ];

                    if ( $i == 4 )
                        $NewsSinglePagetwo = $listeNews[ $i ];

                    if ( $i > 4 and $i < 7 )
                        $NewsPagetwo[ $i ] = $listeNews[ $i ];
                    if ( $i > 6 and $i < 12 )
                        $NewsPagequatro[ $i ] = $listeNews[ $i ];
                }
            }

            $vipUsers = $em->getRepository ( 'UserBundle:User' )->getListVIP ( $Liste_id_VIP );


            return $this->render ( 'MagazineBundle:Magazine:index.html.twig' , array(
                "listConcert" => $list_concert ,
                "listCinema" => $list_cinema ,
                "listSpectacle" => $list_spectacle ,
                "listProgramme" => $list_programme ,

                'listProgrammes' => $listProgrammes ,
                'listCinemas' => $listCinemas ,
                'listSpectacles' => $listSpectacles ,
                'listRadios' => $listRadios ,
                'listPeoples' => $listPeoples ,
                'listConcerts' => $listConcerts ,

                'firstVipUser' => $firstVipUser ,
                'firstVipUserNews' => $firstVipUserNews ,

                'lastNews' => $lastNews ,
                'NewsPageOne' => $NewsPageOne ,
                'NewsSinglePagetwo' => $NewsSinglePagetwo ,
                'NewsPagetwo' => $NewsPagetwo ,
                'NewsPagequatro' => $NewsPagequatro ,

                'vipUsers' => $vipUsers ,


                'crosswords' => $crosswords ,
                'resultatwords' => $resultatwords ,

                'rendezvous' => $rendezvous ,


                "ListeVIP" => $Liste_VIP ,
                "coverImage" => $firstCoverImage ,
            ) );
        } else {
            return $this->render ( 'MagazineBundle:Magazine:error.html.twig' );
        }
    }



    public function magazineLivreAction (  $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $Livre = $em->getRepository ( 'BackBundle:Collectionmedia' )->find ( $id );
        $description = html_entity_decode($Livre->getContenuBook());
        $max_caracteres=50;
        $listeDescription=null; 
           $i = 0 ;
           
        if (strlen($description)>$max_caracteres)
        {
            $position_debut = 0 ;
            do {

                $itemDescription = substr($description, $position_debut, $max_caracteres);

                $position_debut = $position_debut + $max_caracteres ;
                 $i = $i + 1  ;
                if(strlen($description) > $position_debut)
                    $listeDescription[$i] = $itemDescription."..." ;
                else
                    $listeDescription[$i] = $itemDescription ;

             } while (strlen($description) > $position_debut);
        }else{
            $listeDescription[$i] = $description ;
        }
        
            return $this->render ( 'MagazineBundle:Livre:index.html.twig' , array(
                "livre" => $Livre , 
                "listeDescription" => $listeDescription ,

            ) );

}

    public function magazineLivreBdAction (  $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $Livre = $em->getRepository ( 'BackBundle:Collectionmedia' )->find ( $id );
        $bookImagesBD = $em->getRepository ( 'BackBundle:BookImagesBD' )->findBy ( array("book" => $id), array("nbrShow" => "ASC") );




            return $this->render ( 'MagazineBundle:LivreBD:index.html.twig' , array(
                "livre" => $Livre ,
                "bookImagesBD" => $bookImagesBD ,


            ) );

}


}
