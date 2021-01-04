<?php

namespace CastingBundle\Controller;

use BackBundle\Entity\Messaging;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CastingBundle\Entity\Casting;
use CastingBundle\Entity\ReponseVideoCasting;
use CastingBundle\Entity\CastingReponse;
use CastingBundle\Entity\VideoCasting;

class DefaultController extends Controller
{
    
    public function indexAction(Request $request)
    {

         $em = $this->getDoctrine()->getManager();
        $castings = $em->getRepository('CastingBundle:Casting')->findBy(array("member" => $this->getUser()) ,array( 'id' => 'DESC'));
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);
        $personnagesMedia = null;
        return $this->render('CastingBundle:Default:index.html.twig', array(

            "personnagesMedia" => $personnagesMedia,
            "castings" => $castings,
            "listeVIP" => $liste_VIP,
            "member" => $this->getUser()
        ));
    }
    public function listeCastingAction(Request $request)
    {

         $em = $this->getDoctrine()->getManager();
        $castings = $em->getRepository('CastingBundle:Casting')->findBy(array("status" => 1) ,array( 'id' => 'DESC'));
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);
        $personnagesMedia = null;
         $gallerysBanners = $em->getRepository ( 'BackBundle:Collectionmedia' )->findBy(array('type' => 'photos' , 'showPageBanniere' => 1) ,array(), 14);



        return $this->render('CastingBundle:Default:liste_casting.html.twig', array(

            "personnagesMedia" => $personnagesMedia,
            "castings" => $castings,
            "listeVIP" => $liste_VIP,
            'gallerys_banner' => $gallerysBanners,
            
        ));
    }
    public function deleteCastingAction(Request $request)
    {

         $em = $this->getDoctrine()->getManager();
         $id = $request->get('id-item-casting');
        $casting = $em->getRepository('CastingBundle:Casting')->find($id);

        if($casting  != null ){
            $em->remove($casting);
            $em->flush();
        }
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'Castingadd' , $translator->trans ( 'alert.delete' ) );
        return $this->redirectToRoute('casting_homepage');
         
    }
    public function deleteVidoeCastingAction(Request $request)
    {

         $em = $this->getDoctrine()->getManager();
         $id = $request->get('id-item-video-casting');
        $videoCasting = $em->getRepository('CastingBundle:VideoCasting')->find($id);

        if($videoCasting  != null ){
            $em->remove($videoCasting);
            $em->flush();
        }
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'Castingadd' , $translator->trans ( 'alert.delete' ) );
        return $this->redirectToRoute('casting_homepage');
         
    }

    public function postulerCastingAction(Request $request , $id , $counteurVideo)
    {

         $em = $this->getDoctrine()->getManager();

        $casting = $em->getRepository('CastingBundle:Casting')->find($id);
        $videoCasting = $em->getRepository('CastingBundle:VideoCasting')->findBy(array('casting' =>$id));
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);
        $gallerysBanners = $em->getRepository ( 'BackBundle:Collectionmedia' )->findBy(array('type' => 'photos' , 'showPageBanniere' => 1) ,array(), 14);
        $responseCasting = $em->getRepository('CastingBundle:CastingReponse')->findOneBy(array("casting" => $id , "member" => $this->getUser()));
     
        if( $responseCasting == null )           
              $responseCasting = new CastingReponse();
        

        if($request->getMethod() == "POST"){
            $idVideo = $request->get('videoCasting');
            $video = $em->getRepository('CastingBundle:VideoCasting')->find($idVideo);
            $counteurCasting = $request->get('counteurCasting');

            $picture = $_FILES['video']['name'];
            $tmp_name_array = $_FILES['video']['tmp_name'];
            $size_array = $_FILES['video']['size'];


            if ($size_array  > 0) {
                $extension = pathinfo($picture , PATHINFO_EXTENSION);
                $pictureName = md5(uniqid()) . "." . $extension;
                move_uploaded_file($tmp_name_array , 'upload/' . $pictureName);

                if($counteurCasting == 1 &&  $responseCasting == null ){
                    $responseCasting = new CastingReponse();

                    $responseCasting->setMember($this->getUser());
                    $responseCasting->setCasting($casting);

                    $em->persist($responseCasting);
                    $em->flush();
                    $this->get('session')->set('idCastingReponse',$responseCasting->getId());
                } 

                $response = $em->getRepository('CastingBundle:ReponseVideoCasting')->findOneBy(array("videoCasting" => $$video , 'castingReponse' => $responseCasting  , "member" => $this->getUser()));
                
                if( $response == null )
                       $response = new ReponseVideoCasting();

                $response->setMember($this->getUser());
                $response->setVideoCasting($video);
                $response->setCastingReponse($responseCasting);

                $response->setPath($pictureName);

                $em->persist($response);
                $em->flush();

            }

            if($counteurCasting < count($videoCasting) ){

                return $this->redirectToRoute('casting_postuler_scenario' , array('id' => $id , 'counteurVideo' => $counteurCasting ));
            }else{
                $this->get('session')->set('idCastingReponse',null);
                $translator = $this->get ( 'translator' );
                $this->get ( 'session' )->getFlashBag ()->add ( 'Casting_postuler' , $translator->trans ( 'alert.postuler_casting' ) );
                return $this->redirectToRoute('casting_liste_casting' );

            }

        }

        $castingReponse = $em->getRepository('CastingBundle:CastingReponse')->findBy(array( "casting" => $casting->getId() ));
      
        return $this->render('CastingBundle:Default:postuler.html.twig', array(
            'castingReponse' => $castingReponse ,
            'gallerys_banner' => $gallerysBanners,
            "member" => $this->getUser() ,
            "casting" => $casting ,
            "responseCasting" => $responseCasting ,
            "listeVIP" => $liste_VIP,
            "videoCasting" => $videoCasting[$counteurVideo],
            "countVideoCasting" => count($videoCasting) ,
            "counteurVideo" => $counteurVideo,
            "id" => $id,
        ));
    }
    
    public function castingDeletePostulerAction(Request $request )
    {

         $em = $this->getDoctrine()->getManager();
         $id=$request->get('id-item-casting');
            $responseCasting = $em->getRepository('CastingBundle:CastingReponse')->findOneBy(array("casting" => $id , "member" => $this->getUser()));
             $em->remove($responseCasting);
            $em->flush();
              $translator = $this->get ( 'translator' );
              $this->get ( 'session' )->getFlashBag ()->add ( 'Casting_postuler' , $translator->trans ( 'alert.delete' ) );
              return $this->redirectToRoute('casting_liste_casting' );

    }

    public function castingValiderPostulerAction(Request $request  )
    {

         $em = $this->getDoctrine()->getManager();
         $id=$request->get('id-item-casting-validation');
         $responseCasting = $em->getRepository('CastingBundle:CastingReponse')->findOneBy(array("casting" => $id , "member" => $this->getUser()));
            $responseCasting->setStatus(1);
            $em->persist($responseCasting);
            $em->flush();
              $translator = $this->get ( 'translator' );
              $this->get ( 'session' )->getFlashBag ()->add ( 'Casting_postuler' , $translator->trans ( 'alert.postuler__valide_casting' ) );
              return $this->redirectToRoute('casting_liste_casting' );

    }

    public function createOrUpdateAction(Request $request ,$id  )
    {


         $em = $this->getDoctrine()->getManager();
         if($id == null )
        $item = new Casting();
        else
        $item = $em->getRepository('CastingBundle:Casting')->find($id);
            
        if($request->getMethod() == "POST"){
            
            $status = $request->get('status');
            $scenario = $request->get('scenario');
            $lieuTournage = $request->get('lieu_tournage');
            $descriptione = $request->get('description');


            $datesTournageStart = $request->get('dates_tournage_start');
            $datesTournageEnd = $request->get('dates_tournage_end');

           
            $datesTournageStart = \DateTime::createFromFormat('Y-m-d', $datesTournageStart);
            $datesTournageEnd = \DateTime::createFromFormat('Y-m-d', $datesTournageEnd);
            $tarifPrestation = $request->get('tarif_prestation');

            $picture = $_FILES['image']['name'];
            $tmp_name_array = $_FILES['image']['tmp_name'];
            $size_array = $_FILES['image']['size'];


                if ($size_array  > 0) {
                     $extension = pathinfo($picture , PATHINFO_EXTENSION);
                    $pictureName = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array , 'upload/' . $pictureName);
                    $item->setPath($pictureName);

                }


            $picture = $_FILES['pathPdf']['name'];
            $tmp_name_array = $_FILES['pathPdf']['tmp_name'];
            $size_array = $_FILES['pathPdf']['size'];


                if ($size_array  > 0) {
                     $extension = pathinfo($picture , PATHINFO_EXTENSION);
                    $pictureName = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array , 'upload/' . $pictureName);
                    $item->setPathPdf($pictureName);

                }

                $item->setStatus($status);
                $item->setMember($this->getUser());

                $item->setDatesTournageDebut(new \DateTime($datesTournageStart));
                $item->setDescription($descriptione);

              $item->setDatesTournageEnd(new \DateTime($datesTournageEnd));
          
          if($lieuTournage != null )
              $item->setLieuTournage($lieuTournage);
          
          if($tarifPrestation != null )
              $item->setTarif($tarifPrestation);
          
          if($scenario != null )
              $item->setScenario($scenario);
                $em->persist($item);
                $em->flush();


            return $this->redirectToRoute('add_casting_scenario' , array('id' => $item->getId() ));
        }
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);

        return $this->render('CastingBundle:Default:ajouter_casting.html.twig', array(
            "member" => $this->getUser() ,
            "item" => $item,
            "listeVIP" => $liste_VIP,

            'id' => $id

        ));
    }

    public function addScenarioCastingAction(Request $request , $id )
    {

        $em = $this->getDoctrine()->getManager();

        if($request->getMethod() == "POST"){
            $type = $request->get('typeVideo');
            $idCasting = $request->get('idCasting');


            $title = $request->get('scenario');
            $casting = $em->getRepository('CastingBundle:Casting')->find($idCasting);
            $entity = new VideoCasting();
            $picture = $_FILES['image']['name'];
            $tmp_name_array = $_FILES['image']['tmp_name'];
            $size_array = $_FILES['image']['size'];


            if ($size_array  > 0) {
                $extension = pathinfo($picture , PATHINFO_EXTENSION);
                $pictureName = md5(uniqid()) . "." . $extension;
                move_uploaded_file($tmp_name_array , 'upload/' . $pictureName);
                $entity->setPath($pictureName);
                
                $entity->setTitle($title);
                $entity->setCasting($casting);

                $em->persist($entity);
                $em->flush();

            }



            if($type == 'terminer'){
                $translator = $this->get ( 'translator' );
                $this->get ( 'session' )->getFlashBag ()->add ( 'Castingadd' , $translator->trans ( 'alert.add' ) );
                return $this->redirectToRoute('casting_homepage');
            }

            else{
                return $this->redirectToRoute('add_casting_scenario' , array('id' => $idCasting )) ;
            }

        }
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);

        return $this->render('CastingBundle:Default:ajouter_scenario_casting.html.twig', array(
            "listeVIP" => $liste_VIP,
            "member" => $this->getUser() ,
            "id" => $id ,

        ));
    }

    public function listeCandidatsAction($id){
        $em = $this->getDoctrine()->getManager();
        $casting = $em->getRepository('CastingBundle:Casting')->find($id);
        $castingReponse = $em->getRepository('CastingBundle:CastingReponse')->findBy(array( "casting" => $id , 'status' => 1 ));
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);

        $personnagesMedia = null;

        $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );


        return $this->render('CastingBundle:Default:liste_candidats.html.twig', array(

            'gallerysBanners' => $gallerysBanners ,
            "personnagesMedia" => $personnagesMedia,
            "casting" => $casting,
            "castingReponse" => $castingReponse,
            "listeVIP" => $liste_VIP,

        ));

    }



    public function listeCandidatsEnvoyerAction(){
      
      
        $em = $this->getDoctrine()->getManager();
        $castings = $em->getRepository('CastingBundle:CastingReponse')->findBy( array('member' => $this->getUser()->getId()) );
      //   count( $castings[0]->getReponseVideoCasting())    ; exit();
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);
        $personnagesMedia = null;
         $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );


        return $this->render('CastingBundle:Default:liste_candidats_envoyer.html.twig', array(

            'gallerysBanners' => $gallerysBanners ,
             "personnagesMedia" => $personnagesMedia,
            "castings" => $castings,
            "listeVIP" => $liste_VIP,
        ));   
        

    }



    public function castingsTvAction ( Request $request , $idCastingReponse )
    {
        $em = $this->getDoctrine ()->getManager ();

        $this->get ( 'session' )->set ( 'activerouteCourant' , "box" );
        $entity = $em->getRepository('CastingBundle:CastingReponse')->find( $idCastingReponse );
        $user = $em->getRepository('UserBundle:User')->find( $entity->getMember()->getId() );
        $castings = $em->getRepository('CastingBundle:ReponseVideoCasting')->findBy( array('castingReponse' => $idCastingReponse ) );
        $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );
        $Liste_Photos = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMedia("photos", $entity->getMember()->getId());

        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);


        return $this->render ( 'CastingBundle:Default:crossingTv.html.twig' , array(
            "listeVIP" => $liste_VIP,
            'castingReponse' => $entity ,
            'user' => $user ,
            'idCastingReponse' => $idCastingReponse ,
            'gallerysBanners' => $gallerysBanners ,
            'listePhotos' => $Liste_Photos ,
            'castings' => $castings ,
        ) );
    }


    /**
     * @param Request $request
     * @param $idUserreceiver
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function msgenvoyeAction ( Request $request , $idUserreceiver , $idCastingReponse )
    {
        $em = $this->getDoctrine ()->getManager ();
        $useremitter = $this->getUser ();
        $message = $request->get ( 'message' );
        $userreceiver = $em->getRepository ( 'UserBundle:User' )->find ( $idUserreceiver );
         $messaging = new Messaging();
        $messaging->setUseremitter ( $useremitter );
        $messaging->setUserreceiver ( $userreceiver );
        $messaging->setMessage ( $message );
        $em->persist ( $messaging );
        $em->flush ();


        $bodyMessage = $this->renderView ( 'MembreBundle:Messaging:messageEmailCasting.html.twig' , array(
                'messaging' => $messaging
            )
        );

        $message = \Swift_Message::newInstance ()
            ->setSubject ("VIP Crossing -   "   )
            ->setFrom (  $useremitter->getEmail () )
            ->setTo ( $userreceiver->getEmail () )
            ->setBody ( $bodyMessage , 'text/html');
        $this->get ( 'mailer' )->send ( $message );



        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'messagingcastingalerte' , $translator->trans ( 'alert.casting_send_message' ) );

            return $this->redirectToRoute ( "casting_tv" , array( 'idCastingReponse' => $idCastingReponse));



    }

    /**
     * @param Request $request
     * @param $idUserreceiver
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function castingNotificationAction ( Request $request , $idUserreceiver , $idCastingReponse )
    {
        $em = $this->getDoctrine ()->getManager ();
        $useremitter = $this->getUser ();
        $message = $request->get ( 'message' );
        $resultat = $request->get ( 'resultat' );
         if( $resultat == 1 )
        $msgResultat= "  Accepté ";
        else
        $msgResultat= "Annulé";



        $responseCasting = $em->getRepository('CastingBundle:CastingReponse')->find($idCastingReponse);
        $responseCasting->setResultat($resultat);
        $em->merge($responseCasting);
         $em->flush();

        $userreceiver = $em->getRepository ( 'UserBundle:User' )->find ( $idUserreceiver );
        $messaging = new Messaging();
        $messaging->setUseremitter ( $useremitter );
        $messaging->setUserreceiver ( $userreceiver );
        $messaging->setMessage ( $message );
        $em->persist ( $messaging );
        $em->flush ();


        $bodyMessage = $this->renderView ( 'MembreBundle:Messaging:messageEmailNotification.html.twig' , array(
                'messaging' => $messaging,
                'resultat' => $msgResultat,
                'idCastingReponse' => $idCastingReponse
            )
        );

        $message = \Swift_Message::newInstance ()
            ->setSubject ("VIP Crossing -   "   )
            ->setFrom (  $useremitter->getEmail () )
          ->setTo ( $userreceiver->getEmail () )
             ->setBody ( $bodyMessage , 'text/html');
        $this->get ( 'mailer' )->send ( $message );



        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'messagingcastingalerte' , $translator->trans ( 'alert.casting_send_message' ) );

            return $this->redirectToRoute ( "casting_tv" , array( 'idCastingReponse' => $idCastingReponse));



    }
}
