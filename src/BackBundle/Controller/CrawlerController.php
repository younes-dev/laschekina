<?php

/**
 * Controller for home page.
 * 
 * @package FrontBundle
 * @author Mouadh Ben Alaya
 */
namespace BackBundle\Controller;

use BackBundle\Entity\Crawlercinema;
use BackBundle\Entity\Crawlerconcert;
use BackBundle\Entity\Crawlernews;
use BackBundle\Entity\Crawlerprogramme;
use BackBundle\Entity\Crawlerradio;
use BackBundle\Entity\Crawlerspectacle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for home page.
 * 
 * @package CrawlerBundle
 * @author Mouadh Ben Alaya
 */
class CrawlerController extends Controller
{

    /**********Cinema France **********/

    public function CinemaAction ()
    {

     //   $this->offiAction ();
        $this->cinefilAction ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'crawlercinemasuccess' , $translator->trans ( 'alert.add' ) );

        return $this->redirectToRoute ( 'crawlercinema_index' );
    }

    
    /**
     * cinefil page.
     * @return Response A Response instance
     **/
    public function cinefilAction()
    {
        $html = file_get_contents('http://www.cinefil.com/sorties-cinema-du-mois');
        $crawler = new Crawler($html);

        $items = $crawler->filter('div.nouvel-interface2')->each(function($node) {
                /***** title *****/
            $titleNode = $node->filter('div.fiche-infos2 a');
            $title = ($titleNode->count()) ? $titleNode->html() : "";

            /***** directed *****/
            $directedNode = $node->filter('div.fiche-infos2 p span a');
            $directed = ($directedNode->count()) ? $directedNode->html() : "";

            /***** sortie *****/
            $sortieNode = $node->filter('div.fiche-infos2 p.perte-mobil');
            $sortie = ($sortieNode->count()) ? $sortieNode->html() : "";

            /***** description *****/
            $descriptionNode = $node->filter ( 'div.Synopsis-infos2  p' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";

            /***** Lien plus information  *****/
            $urlNode = $node->filter ( 'div.fiche-info-part-2  a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter('div.img-film2 a img');
            $image = ($imageNode->count()) ? $imageNode->attr('src') : "";

            $infos = array(
                'title' => $title,
                'url' => $url ,
                'description' => $description ,
                'directed' => $directed,
                'sortie' => $sortie,
                'image' => $image ,
            );

            return $infos;
        });
        foreach ($items as $key => $value) {
            $description = strip_tags ( $value[ "directed" ] ) . "<br/>" . strip_tags ( $value[ "sortie" ] ) . "<br/>" . strip_tags ( $value[ "description" ] ) . "<br/>";
            $url = $value[ "url" ];
            $title = strip_tags ( $value[ "title" ] );
            $picture = $value[ "image" ];
            $em = $this->getDoctrine ()->getManager ();
            $entity = $em->getRepository ( 'BackBundle:Crawlercinema' )->findBy ( array( "url" => $url ) );
            if ( $entity == null && $picture != null ) {

                $cinema = new Crawlercinema();
                $cinema->setTitle ( trim ( $title ) );
                $cinema->setUrl ( trim ( $url ) );
                $cinema->setPicture ( trim ( $picture ) );
                $cinema->setTypePicture('web');
                $cinema->setDescription ( trim ( $description ) );
                $cinema->setPays ( 'fr' );
                $cinema->setChannel ( 'cinefil' );
                $cinema->setShortdescription (  trim ( $title ) );
                
                $em->persist ( $cinema );
                $em->flush ();

            }
        }

        return new Response();
    }

    /**
     * offi page.
     * @return Response A Response instance
     **/
    public function offiAction ()
    {
        $html = file_get_contents ( 'http://www.offi.fr/cinema' );
        $crawler = new Crawler( $html );

        $items = $crawler->filter ( 'div.blockHome' )->each ( function ( $node ) {

            /***** title *****/

            $titleNode = $node->filter ( 'h2 a span' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'h2 a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** description *****/

            $descriptionNode = $node->filter ( 'div.blockHomeInside ' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";

            /***** image *****/

            $imageNode = $node->filter ( 'a img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'title' => $title ,
                'url' => $url ,
                'image' => $image ,
                'description' => $description ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            $description = $value[ "description" ];
            $url = 'http://www.offi.fr' . $value[ "url" ];
            $title = $value[ "title" ];
            $picture = $value[ "image" ];
            $em = $this->getDoctrine ()->getManager ();
            $entity = $em->getRepository ( 'BackBundle:Crawlercinema' )->findBy ( array( "url" => $url ) );
            if ( $entity == null &&  $picture != null ) {

                $cinema = new Crawlercinema();
                $cinema->setTitle ( trim ( $title ) );
                $cinema->setUrl ( trim ( $url ) );
                $cinema->setPicture ( trim ( $picture ) );
                $cinema->setDescription ( trim ( $description ) );
                $cinema->setTypePicture('web');
                $cinema->setPays ( 'fr' );
                $cinema->setChannel ( 'offi' );
                $cinema->setShortdescription (  trim ( $title ) );
                
                $em->persist ( $cinema );
                $em->flush ();

            }
        }

        return new Response();
    }


    /**
     * cinemasgaumontpathe page.
     * @return Response A Response instance
     **/
    public function cinemasgaumontpatheAction ()
    {
        $contextOptions = array(
            "ssl" => array(
                "verify_peer" => false ,
                "verify_peer_name" => false
            ) ,
        );
        $html = file_get_contents ( "https://www.cinemasgaumontpathe.com/films/" , false , stream_context_create ( $contextOptions ) );

        $crawler = new Crawler( $html );

        $items = $crawler->filter ( 'div.slider-films' )->each ( function ( $node ) {

            /***** title *****/

            $titleNode = $node->filter ( 'h3 span' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            $infos = array(
                'title' => $title ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            echo $key . " : <br/>";
            echo "titte : " . $value[ "title" ] . "<br/>";

            echo "--------------------<br/>";
        }

        return new Response();
    }


    /**********Spectacle France ************/

    public function SpectacleAction ()
    {
        $this->francebilletAction ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'crawlerspectaclesuccess' , $translator->trans ( 'alert.add' ) );

        return $this->redirectToRoute ( 'crawlerspectacle_index' );

    }

    /**
     * francebillet page.
     * @return Response A Response instance
     **/
    public function francebilletAction ()
    {
        $html = file_get_contents ( 'http://www.francebillet.com/place-spectacle/Autres/Grands-Spectacles-p1541243500752972317.htm' );
        $crawler = new Crawler( $html );

        $items = $crawler->filter ( 'tbody.manifs tr' )->each ( function ( $node ) {

            /***** title *****/


            $titleNode = $node->filter ( 'td.desc dl a' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";
            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'td.img a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** description *****/


            $descriptionNode = $node->filter ( 'td.desc dd span a' )->eq ( 1 );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";

            $hourNode = $node->filter ( 'td.desc dd' )->eq ( 2 );
            $hour = ( $hourNode->count () ) ? $hourNode->html () : "";

            /***** image *****/

            $imageNode = $node->filter ( 'td.img a img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'title' => $title ,
                'hour' => $hour ,
                'url' => $url ,
                'image' => $image ,
                'description' => $description ,
            );

            return $infos;
        } );

        foreach ( $items as $key => $value ) {
            if ( $value[ "url" ] != "" ) {

                $title = strip_tags ( $value[ "title" ] );

                $url = "http://www.francebillet.com" . $value[ "url" ];
                $picture = "http://www.francebillet.com" . $value[ "image" ];
                $description = "hour : " . $value[ "hour" ] . "<br/>" . $value[ "description" ];

                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlerspectacle' )->findBy ( array( "url" => $url ) );
                if ( $entity == null && $picture != null ) {

                    $spectacle = new Crawlerspectacle();
                    $spectacle->setTitle ( trim ( $title ) );
                    $spectacle->setUrl ( trim ( $url ) );
                    $spectacle->setPicture ( trim ( $picture ) );
                    $spectacle->setTypePicture('web');
                    $spectacle->setDescription ( strip_tags ( trim ( $description ) ) );
                    $spectacle->setPays ( 'fr' );
                    $spectacle->setChannel ( 'francebillet' );
                    $spectacle->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $spectacle );
                    $em->flush ();

                }
            }
        }

        return new Response();
    }


    /**********Concerts France and Espagne ************/

    public function concertsAction ()
    {

       // $this->offiConcertsAction ();
        $this->infoconcertAction ();
        $this->upconcertespagneAction ();
        $this->upconcertAction ();
        $this->lyloAction ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'crawlerconcertssuccess' , $translator->trans ( 'alert.add' ) );

        return $this->redirectToRoute ( 'crawlerconcert_index' );
    }

    /**
     * offiSpectacle page.
     * @return Response A Response instance
     **/
    public function offiConcertsAction ()
    {
        for($i = 1 ; $i < 11 ; $i++){
        $siteUrl = 'http://www.offi.fr/concerts?npage='.$i;
        $html = file_get_contents ( $siteUrl );
        $crawler = new Crawler( $html );

        $items = $crawler->filter ( 'div.oneRes' )->each ( function ( $node ) {

            /***** title *****/


            $titleNode = $node->filter ( 'div.eventTitle strong a span ' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";
            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'div.resVignetteConcerts a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** description *****/


            $descriptionNode = $node->filter ( 'ul.detail li' )->eq ( 0 );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";

            $descriptionNode = $node->filter ( 'ul.detail li' )->eq ( 1 );
            $description1 = "<br/>" . ( $descriptionNode->count () ) ? $descriptionNode->html () : "";

            $descriptionNode = $node->filter ( 'ul.detail li' )->eq ( 2 );
            $description2 = "<br/>" . ( $descriptionNode->count () ) ? $descriptionNode->html () : "";


            /***** image *****/

            $imgNode = $node->filter ( 'div.resVignetteConcerts a img ' );
            $img = ( $imgNode->count () ) ? $imgNode->attr ( 'src' ) : "";

            $infos = array(
                'title' => $title ,
                'url' => $url ,
                'img' => $img ,
                'description' => $description ,
                'description1' => $description1 ,
                'description2' => $description2 ,
            );

            return $infos;
        } );

        foreach ( $items as $key => $value ) {
             if ( $value[ "title" ] != "" ) {
             $title = $value[ "title" ] ;
             $url = "http://www.offi.fr" . $value[ "url" ];
             $img = $value[ "img" ] ;
            $description = $value[ "description" ] . "<br/>" . $value[ "description1" ] . "<br/>" . $value[ "description2" ] . "<br/>";

             $description = "Site Web : <a href='" . $url . "' >Lien de site </a> <br/>" . $description;

            $em = $this->getDoctrine ()->getManager ();
                  $entity = $em->getRepository ( 'BackBundle:Crawlerconcert' )->findBy ( array( "url" => $url ) );
                  if ( $entity == null && $img != null  ) {

                      $concert = new Crawlerconcert();
                      $concert->setTitle ( trim ( $title ) );
                      $concert->setUrl ( trim ( $url ) );
                      $concert->setDescription ( trim ( $description ) );
                      $concert->setPicture ( $img );
                      $concert->setTypePicture('web');
                      $concert->setPays ( 'fr' );
                      $concert->setChannel ( 'offi' );
                      
                      $concert->setShortdescription (  trim ( $title ) );
                      $em->persist ( $concert );
                      $em->flush ();

                  }
             }


        }
        }

        return new Response();
    }


    /**
     * infoconcert page.
     * @return Response A Response instance
     **/
    public function infoconcertAction ()
    {
        $html = file_get_contents ( 'http://www.infoconcert.com/ville/paris-5133/concerts.html' );
        $crawler = new Crawler( $html );

        $items = $crawler->filter ( 'div.concert-row.no-border' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div.col-center.summary div.artists a' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";


            /***** sortie *****/
            $sortieNode = $node->filter ( 'div.col-left.date' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";


            /***** Lien plus information  *****/
            $urlNode = $node->filter ( 'div.col-center.summary div.artists a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/

            $infos = array(
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {
                $description = strip_tags ($value[ "sortie" ]);
                $url = "http://www.infoconcert.com" . $value[ "url" ];
                $title = strip_tags ( $value[ "title" ] );
                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlerconcert' )->findBy ( array( "url" => $url ) );
                if ( $entity == null ) {

                    $cinema = new Crawlerconcert();
                    $cinema->setTitle ( trim ( $title ) );
                    $cinema->setUrl ( trim ( $url ) );
                    $cinema->setDescription ( trim ( $description ) );
                    $cinema->setChannel ( 'infoconcert' );
                    $cinema->setPays ( 'fr' );
                    $cinema->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $cinema );
                    $em->flush ();


                }
            }
        }

        return new Response();
    }

    /**
     * lylo page.
     * @return Response A Response instance
     **/
    public function lyloAction ()
    {
        $html = file_get_contents ( 'http://www.lylo.fr/agenda-concerts' );
        $crawler = new Crawler( $html );
        $sortieNode = $crawler->filter ( 'div.day h2' );
        $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";

        $items = $crawler->filter ( 'div.event' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div.event-detail h3 a' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";


            /***** sortie *****/

            /***** description *****/
            $descriptionNode = $node->filter ( 'div.event-detail  p' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";

            /***** Lien plus information  *****/
            $urlNode = $node->filter ( 'div.event-detail h3 a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'p.image span img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'title' => $title ,
                'url' => $url ,
                'description' => $description ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {
                $description = strip_tags ($sortie) . '<br/>' . strip_tags ($value[ "description" ]);
                $url = "http://www.lylo.fr" . $value[ "url" ];
                $title = strip_tags ( $value[ "title" ] );
                $picture = $value[ "image" ];
                echo $picture . "<br/>";
                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlerconcert' )->findBy ( array( "url" => $url ) );
                if ( $entity == null && $picture != null) {

                $cinema = new Crawlerconcert();
                $cinema->setTitle ( trim ( $title ) );
                $cinema->setUrl ( trim ( $url ) );
                $cinema->setPicture ( trim ( $picture ) );
                $cinema->setTypePicture('web');
                $cinema->setDescription ( trim ( $description ) );
                $cinema->setChannel ( 'lylo' );
                $cinema->setPays ( 'fr' );
                $cinema->setShortdescription (  trim ( $title ) );
                
                $em->persist ( $cinema );
                $em->flush ();

            }
            }
        }

        return new Response();
    }

    /**
     * upconcert page.
     * @return Response A Response instance
     **/
    public function upconcertAction ()
    {
        $html = file_get_contents ( 'http://www.upconcert.fr/france' );
        $crawler = new Crawler( $html );

        $items = $crawler->filter ( 'div.elt' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'td  h3 a' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";


            /***** sortie *****/
            $sortieNode = $node->filter ( 'td div.flyer span.date' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";


            /***** Lien plus information  *****/
            $urlNode = $node->filter ( 'td  h3 a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'div.flyer img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {
            $description = strip_tags ($value[ "sortie" ]);
            $url = "http://www.upconcert.fr" . $value[ "url" ];
            $title = strip_tags ( $value[ "title" ] );
            $picture = $value[ "image" ];
            $em = $this->getDoctrine ()->getManager ();
            $entity = $em->getRepository ( 'BackBundle:Crawlerconcert' )->findBy ( array( "url" => $url ) );
            if ( $entity == null &&  $picture  != null ) {

                $cinema = new Crawlerconcert();
                $cinema->setTitle ( trim ( $title ) );
                $cinema->setUrl ( trim ( $url ) );
                $cinema->setPicture ( trim ( $picture ) );
                $cinema->setTypePicture('web');
                $cinema->setDescription ( trim ( $description ) );
                $cinema->setPays ( 'fr' );
                $cinema->setChannel ( 'upconcert' );
                $cinema->setShortdescription (  trim ( $title ) );
                
                $em->persist ( $cinema );
                $em->flush ();

            }
        }
        }

        return new Response();
    }

    /**
     * upconcert page. Espagne
     * @return Response A Response instance
     **/
    public function upconcertespagneAction ()
    {

        $html = file_get_contents ( 'http://www.upconcert.fr/espagne' );
        $crawler = new Crawler( $html );

        $items = $crawler->filter ( 'div.elt' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'td  h3 a' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";


            /***** sortie *****/
            $sortieNode = $node->filter ( 'td div.flyer span.date' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";


            /***** Lien plus information  *****/
            $urlNode = $node->filter ( 'td  h3 a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'div.flyer img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {
                $description = $value[ "sortie" ];
                $url = "http://www.upconcert.fr" . $value[ "url" ];
                $title = strip_tags ( $value[ "title" ] );
                $picture = $value[ "image" ];
                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlerconcert' )->findBy ( array( "url" => $url ) );
                if ( $entity == null && $picture != null ) {

                $cinema = new Crawlerconcert();
                $cinema->setTitle ( trim ( $title ) );
                $cinema->setUrl ( trim ( $url ) );
                $cinema->setPicture ( trim ( $picture ) );
                $cinema->setTypePicture('web');
                $cinema->setDescription ( trim ( $description ) );
                $cinema->setPays ( 'es' );
                $cinema->setChannel ( 'upconcert' );
                $cinema->setShortdescription (  trim ( $title ) );
                
                $em->persist ( $cinema );
                $em->flush ();

                }
            }
        }

        return new Response();
    }

    /**********radio France  ************/
    public function radioAction ()
    {
        $this->nrjAction ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'crawlerradiosuccess' , $translator->trans ( 'alert.add' ) );

        return $this->redirectToRoute ( 'crawlerradio_index' );

    }

    /**
     * nrj page.
     * @return Response A Response instance
     **/
    public function nrjAction ()
    {
        $html = file_get_contents ( 'http://www.nrj.fr/emissions' );
        $crawler = new Crawler( $html );

        $items = $crawler->filter ( 'div.programItem' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div h2' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            /***** description *****/
            $descriptionNode = $node->filter ( 'div div.presentation-text p' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";


            /***** sortie *****/
            $sortieNode = $node->filter ( 'div p' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";


            /***** Lien plus information  *****/

            $url = "http://www.nrj.fr/emissions";

            /***** image *****/
            $imageNode = $node->filter ( 'div.presentation-mode-xs div.img-container img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'description' => $description ,
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {

            $title = $value[ "title" ];
            $url = $value[ "url" ];
            $description = $value[ "sortie" ] . "<br/>" . $value[ "description" ];
            $picture = $value[ "image" ];
            $em = $this->getDoctrine ()->getManager ();
            $radio = new Crawlerradio();
            $radio->setTitle ( trim ( $title ) );
            $radio->setUrl ( trim ( $url ) );
            $radio->setPicture ( trim ( $picture ) );
            $radio->setDescription ( trim ( $description ) );
            $radio->setPays ( 'fr' );
            $radio->setChannel ( 'nrj' );
            $radio->setShortdescription (  trim ( $title ) );
            
            $em->persist ( $radio );
            $em->flush ();

        }


        return new Response();
    }


    /**********news France  ************/
    public function newsAction ()
    {
        $this->latinaAction ();
        $this->purepeopleAction ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'crawlernewssuccess' , $translator->trans ( 'alert.add' ) );

        return $this->redirectToRoute ( 'crawlernews_index' );

    }

    /**
     * latina page.
     * @return Response A Response instance
     **/
    public function latinaAction ()
    {
        $html = file_get_contents ( 'http://www.latina.fr/' );
        $crawler = new Crawler( $html );
        /******** MUSIQUE ******/
        $items = $crawler->filter ( 'div.actus-format2 div.no-gutters div.item' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'span.title a h3' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            /***** description *****/
            /*   $descriptionNode = $node->filter ( 'div div.presentation-text p' );
               $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";*/


            /***** sortie *****/
            $sortieNode = $node->filter ( 'span.title div.item-bottom span' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";


            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'span.title a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'span.photo a img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                //     'description' => $description ,
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {

                $title = $value[ "title" ];
                $url = "http://www.latina.fr" . $value[ "url" ];
                $description = $value[ "sortie" ];
                $picture = "http://www.latina.fr" . $value[ "image" ];
                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlernews' )->findBy ( array( "url" => $url ) );
                if ( $entity == null && $picture != null ) {
                    $news = new Crawlernews();
                    $news->setTitle ( trim ( $title ) );
                    $news->setUrl ( trim ( $url ) );
                    $news->setPicture ( trim ( $picture ) );
                    $news->setDescription ( trim ( $description ) );
                    $news->setPays ( 'fr' );
                    $news->setChannel ( 'latina' );
                    $news->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $news );
                    $em->flush ();
                }
            }
        }
        /******** LA PAUSE LATINA or BONS PLANS******/
        $items = $crawler->filter ( 'div.actus-format3 div.no-gutters div.item' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'span.title a h3' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            /***** description *****/
            /*   $descriptionNode = $node->filter ( 'div div.presentation-text p' );
               $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";*/


            /***** sortie *****/
            $sortieNode = $node->filter ( 'span.title div.item-bottom span' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";


            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'span.title a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'span.photo a img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                //     'description' => $description ,
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {

                $title = $value[ "title" ];
                $url = "http://www.latina.fr" . $value[ "url" ];
                $description = $value[ "sortie" ];
                $picture = "http://www.latina.fr" . $value[ "image" ];
                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlernews' )->findBy ( array( "url" => $url ) );
                if ( $entity == null && $picture != null ) {
                    $news = new Crawlernews();
                    $news->setTitle ( trim ( $title ) );
                    $news->setUrl ( trim ( $url ) );
                    $news->setPicture ( trim ( $picture ) );
                    $news->setDescription ( trim ( $description ) );
                    $news->setPays ( 'fr' );
                    $news->setChannel ( 'latina' );
                    $news->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $news );
                    $em->flush ();
                }
            }
        }


        return new Response();
    }

    /**
     * purepeople page.
     * @return Response A Response instance
     **/
    public function purepeopleAction ()
    {
        $html = file_get_contents ( 'http://www.purepeople.com/' );
        $crawler = new Crawler( $html );
        /******** MUSIQUE ******/
        $items = $crawler->filter ( 'div.c-article-flux' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div.c-article-flux__body h2 a ' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            /***** description *****/
            $descriptionNode = $node->filter ( 'div.c-article-flux__chapo' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";


            /***** sortie *****/
            $sortieNode = $node->filter ( 'div.c-article-flux__date' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";


            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'div.c-article-flux__body h2 a ' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'div.c-article-flux__media span.c-article-flux__img-main-container img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'description' => $description ,
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {

                $title = $value[ "title" ];
                $url = "http://www.purepeople.com/" . $value[ "url" ];
                $description = $value[ "sortie" ];
                $picture = $value[ "image" ];

                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlernews' )->findBy ( array( "url" => $url ) );
                if ( $entity == null && $picture != null) {
                    $news = new Crawlernews();
                    $news->setTitle ( trim ( $title ) );
                    $news->setUrl ( trim ( $url ) );
                    $news->setPicture ( trim ( $picture ) );
                    $news->setDescription ( trim ( $description ) );
                    $news->setChannel ( 'purepeople' );
                    $news->setPays ( 'fr' );
                    $news->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $news );
                    $em->flush ();
                }
            }
        }


        return new Response();
    }


    /**********Programme TV    ************/
    public function programmeAction ()
    {


        $this->antena3Action ();
        $this->arteAction ();
        $this->mylifetimeAction ();
        $this->usanetworkAction ();
        $this->historyAction ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'crawlerprogrammesuccess' , $translator->trans ( 'alert.add' ) );

        return $this->redirectToRoute ( 'crawlerprogramme_index' );

    }

    /**
     * arte page.
     * @return Response A Response instance
     **/
    public function arteAction ()
    {

        $html = file_get_contents ( 'http://www.arte.tv/fr/guide/' );


        $crawler = new Crawler( $html );
        /******** MUSIQUE ******/
        $items = $crawler->filter ( 'article.tvguide-program' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div.tvguide-program__wrapper div.hide-for-medium h3 ' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            /***** sortie *****/
            $sortieNode = $node->filter ( 'div.tvguide-program__wrapper header.tvguide-program__header span ' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";


            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'article.tvguide-program a ' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'div.next-teaser__thumbnail' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'style' ) : "";

            $infos = array(
                //    'description' => $description ,
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {

                $title = strip_tags ( $value[ "title" ] );
                $url = $value[ "url" ];
                $description = $value[ "sortie" ];
                $picture = substr ( substr ( $value[ "image" ] , 0 , -13 ) , 22 );

                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlerprogramme' )->findBy ( array( "url" => $url ) );
                if ( $entity == null ) {
                    $programme = new Crawlerprogramme();
                    $programme->setTitle ( trim ( $title ) );
                    $programme->setUrl ( trim ( $url ) );
                    $programme->setPicture ( trim ( $picture ) );
                    $programme->setDescription ( trim ( $description ) );
                    $programme->setPays ( 'fr' );
                    $programme->setChannel ( 'arte' );
                    $programme->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $programme );
                    $em->flush ();
                }
            }
        }


        return new Response();
    }

    /**
     * mylifetime page.
     * @return Response A Response instance
     **/
    public function mylifetimeAction ()
    {

        $html = file_get_contents ( 'http://www.mylifetime.com/schedule' );


        $crawler = new Crawler( $html );
        /******** MUSIQUE ******/
        $items = $crawler->filter ( 'li.listing-item' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div.details  h3' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            $titleNode = $node->filter ( 'div.details  h4' );
            $sousTitle = ( $titleNode->count () ) ? $titleNode->html () : "";

            /***** sortie *****/
            $sortieNode = $node->filter ( 'div.time ' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";

            /***** description *****/
            $descriptionNode = $node->filter ( 'div.episode-info-inner p.description' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";


            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'section.links  a.button' )->eq ( 1 );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'div.episode-info-inner div.description-wrapper div.thumbnail a img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'description' => $description ,
                'title' => $title ,
                'sousTitle' => $sousTitle ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {

                $title = $value[ "title" ] . " " . $value[ "sousTitle" ];
                $url = "http://www.mylifetime.com" . $value[ "url" ];
                $image = $value[ "image" ];
                $description = $value[ "sortie" ] . "<br/>" . $value[ "description" ];

                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlerprogramme' )->findBy ( array( "url" => $url ) );
                if ( $entity == null ) {
                    $programme = new Crawlerprogramme();
                    $programme->setTitle ( trim ( $title ) );
                    $programme->setUrl ( trim ( $url ) );
                    $programme->setPicture ( trim ( $image ) );
                    $programme->setDescription ( trim ( $description ) );
                    $programme->setPays ( 'en' );
                    $programme->setChannel ( 'mylifetime' );
                    $programme->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $programme );
                    $em->flush ();
                }
            }
        }

        return new Response();
    }

    /**
     * usanetwork page.
     * @return Response A Response instance
     **/
    public function usanetworkAction ()
    {

        $html = file_get_contents ( 'http://www.usanetwork.com/schedule?guid=1385706191' );


        $crawler = new Crawler( $html );
        /******** MUSIQUE ******/
        $items = $crawler->filter ( 'div.schedule-item' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div.visible-block  div.episode-info div.episode-show a' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";


            /***** sortie *****/
            $sortieNode = $node->filter ( 'div.visible-block div.time-wrapper div.time ' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";

            /***** description *****/
            $descriptionNode = $node->filter ( 'div.hidden-block div.node a  div.meta div.description' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";


            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'div.visible-block  div.episode-info div.episode-show a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'div.hidden-block div.node a img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'description' => $description ,
                'title' => $title ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {

                $title = $value[ "title" ];
                $url = "http://www.usanetwork.com" . $value[ "url" ];
                $image = $value[ "image" ];
                $description = strip_tags ( $value[ "sortie" ] ) . "<br/>" . $value[ "description" ];
                /*    echo "title :" . $title ."<br/>";
                    echo "url : " . $url ."<br/>";
                    echo "description : " . $description ."<br/>";
                    echo "image  : " .$image."<br/>";
                    echo "--------------------------- <br/>" ;*/

                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlerprogramme' )->findBy ( array( "url" => $url ) );
                if ( $entity == null ) {
                    $programme = new Crawlerprogramme();
                    $programme->setTitle ( trim ( $title ) );
                    $programme->setUrl ( trim ( $url ) );
                    $programme->setPicture ( trim ( $image ) );
                    $programme->setDescription ( trim ( $description ) );
                    $programme->setPays ( 'en' );
                    $programme->setChannel ( 'usanetwork' );
                    $programme->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $programme );
                    $em->flush ();
                }
            }
        }

        return new Response();
    }

    /**
     * history page.
     * @return Response A Response instance
     **/
    public function historyAction ()
    {

        $html = file_get_contents ( 'http://www.history.com/schedule' );


        $crawler = new Crawler( $html );
        /******** MUSIQUE ******/
        $items = $crawler->filter ( 'li.listing-item' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div.details  h3' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";

            $titleNode = $node->filter ( 'div.details  h4' );
            $sousTitle = ( $titleNode->count () ) ? $titleNode->html () : "";

            /***** sortie *****/
            $sortieNode = $node->filter ( 'div.time ' );
            $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";

            /***** description *****/
            $descriptionNode = $node->filter ( 'div.episode-info-inner p.description' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";


            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'section.links  a.button' )->eq ( 1 );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            $imageNode = $node->filter ( 'div.episode-info-inner div.description-wrapper div.thumbnail a img' );
            $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";

            $infos = array(
                'description' => $description ,
                'title' => $title ,
                'sousTitle' => $sousTitle ,
                'url' => $url ,
                'sortie' => $sortie ,
                'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "title" ] != "" ) {

                $title = strip_tags ( $value[ "title" ] . " " . $value[ "sousTitle" ] );
                $url = "http://www.history.com" . $value[ "url" ];
                $image = $value[ "image" ];
                $description = $value[ "sortie" ] . "<br/>" . $value[ "description" ];

                /*   echo "title : ".$title . '<br/>';
                   echo "url : ".$url . '<br/>';
                   echo "image : ".$image . '<br/>';
                   echo "description : ".$description . '<br/>';
                   echo "--------------------- <br/>";
                */
                $em = $this->getDoctrine ()->getManager ();
                $entity = $em->getRepository ( 'BackBundle:Crawlerprogramme' )->findBy ( array( "url" => $url ) );
                if ( $entity == null ) {
                    $programme = new Crawlerprogramme();
                    $programme->setTitle ( trim ( $title ) );
                    $programme->setUrl ( trim ( $url ) );
                    $programme->setPicture ( trim ( $image ) );
                    $programme->setDescription ( trim ( $description ) );
                    $programme->setPays ( 'en' );
                    $programme->setChannel ( 'history' );
                    $programme->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $programme );
                    $em->flush ();
                }
            }
        }

        return new Response();
    }

    /**
     * antena3 page.
     * @return Response A Response instance
     **/
    public function antena3Action ()
    {

        $html = file_get_contents ( 'http://www.antena3.com/programacion/#dia/hoy/tarde' );


        $crawler = new Crawler( $html );
        /******** MUSIQUE ******/
        $items = $crawler->filter ( 'li.programacion-item ' )->each ( function ( $node ) {
            /***** title *****/
            $titleNode = $node->filter ( 'div.item-wrapper div.wrapper-item-title a h2.item-title' );
            $title = ( $titleNode->count () ) ? $titleNode->html () : "";


            /***** sortie *****/
            /*    $sortieNode = $node->filter ( 'div.panel-heading a  label' );
                $sortie = ( $sortieNode->count () ) ? $sortieNode->html () : "";*/

            /***** description *****/
            $descriptionNode = $node->filter ( 'div.item-wrapper p.item-description' );
            $description = ( $descriptionNode->count () ) ? $descriptionNode->html () : "";


            /***** Lien plus information  *****/

            $urlNode = $node->filter ( 'div.item-wrapper div.wrapper-item-title a' );
            $url = ( $urlNode->count () ) ? $urlNode->attr ( 'href' ) : "";

            /***** image *****/
            /*  $imageNode = $node->filter ( 'div.panel-heading a  span.img-wrap img ' );
              $image = ( $imageNode->count () ) ? $imageNode->attr ( 'src' ) : "";*/

            $infos = array(
                'description' => $description ,
                'title' => $title ,
                'url' => $url ,
                //     'sortie' => $sortie ,
                //     'image' => $image ,
            );

            return $infos;
        } );
        foreach ( $items as $key => $value ) {
            if ( $value[ "url" ] != "" ) {

                $title = $value[ "title" ];
                $url = $value[ "url" ];
                $description = $value[ "description" ];

                $em = $this->getDoctrine ()->getManager ();
                $entity = null;
                $entity = $em->getRepository ( 'BackBundle:Crawlerprogramme' )->findBy ( array( "url" => $url ) );
                if ( $entity == null ) {
                    $programme = new Crawlerprogramme();
                    $programme->setTitle ( trim ( $title ) );
                    $programme->setUrl ( trim ( $url ) );
                    $programme->setDescription ( trim ( $description ) );
                    $programme->setPays ( 'es' );
                    $programme->setChannel ( 'antena3' );
                    $programme->setShortdescription (  trim ( $title ) );
                    
                    $em->persist ( $programme );
                    $em->flush ();
                }
            }
        }
        return new Response();
    }

    
}
