<?php

/**
 * Controller for home page.
 * 
 * @package FrontBundle
 * @author Mouadh Ben Alaya
 */
namespace FrontBundle\Controller;

use BackBundle\Entity\Contact;
use BackBundle\Entity\LikeMedia;
use BackBundle\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for home page.
 * 
 * @package FrontBundle
 * @author Mouadh Ben Alaya
 */
class AjaxFrontController extends Controller
{
    /**
     * Page ajax List media vip.
     *
     * @param Request $request The current request.
     *
     * @return JsonResponse A JsonResponse instance
     **/
    public function ajaxvipAction (Request $request)
    {
        $id = $request->get('id') ;
        $em = $this->getDoctrine ()->getManager ();
        $photos = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediafix (4, 'photos', $id);
        $photosshowHome = $em->getRepository('BackBundle:Collectionmedia')->findOneBy(array("showPageHome" => 1 , 'member' => $id , "type" =>'photos' ))  ;

        $videos = $em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMediafixVideos ( 6 , 'videos' , $id );

        $news = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediafix (5, 'news', $id);
        $alerteVip = $em->getRepository('BackBundle:Medias')->getMediasVip($id);
        $alerteRendezvous = $em->getRepository ( 'BackBundle:Rendezvous' )->getAlertRDVVip (  $id );
 
       $vipUser = $em->getRepository('UserBundle:User')->find($id);

        $twitterHtml = $this->renderView('FrontBundle:Ajax:twitter.html.twig', array( 'vipUser' => $vipUser));
        $facebookHtml = $this->renderView('FrontBundle:Ajax:facebook.html.twig', array( 'vipUser' => $vipUser));
        $instagramHtml = $this->renderView('FrontBundle:Ajax:instagram.html.twig', array( 'vipUser' => $vipUser));
        $currentPhotoHtml = $this->renderView('FrontBundle:Ajax:photosCourant.html.twig', array('Photos' => $photos , 'vipUser' => $vipUser , "photosshowHome" => $photosshowHome ));
        $photosHtml = $this->renderView('FrontBundle:Ajax:photos.html.twig', array('ListePhotos' => $photos));
        $videosHtml = $this->renderView('FrontBundle:Ajax:videos.html.twig', array('ListeVideos' => $videos));
        $newsHtml = $this->renderView('FrontBundle:Ajax:news.html.twig', array('ListeNews' => $news , 'vipUser' => $vipUser));
        $alerteHtml = $this->renderView('FrontBundle:Ajax:tab1-alerte.html.twig', array('Listealerte' => $alerteVip , 'alerteRendezvous' => $alerteRendezvous));

        return new JsonResponse(array(
            'instagram' => $instagramHtml,
            'facebook' => $facebookHtml,
            'twitter' => $twitterHtml,
            'current' => $currentPhotoHtml,
            'photos' => $photosHtml,
            'videos' => $videosHtml,
            'news' => $newsHtml,
            'alerte' => $alerteHtml
        ));
    }

    /**
     * send$ Contact
     * @param Request $request The current request.
     *
     * @return Response A reponse instance
     */
    public function sendcontactAction(Request $request)
    {
        $name = $request->get('name');
        $objet = $request->get('objet');
        $email = $request->get('email');
        $message = $request->get('message');
        $response = $request->get('captcha');

        $secret="6Lfi9MwUAAAAAPPCf28fdIBE4FYGaoc-P8BKjWC-";
        $resultat = 0 ;

        $verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $captcha_success=json_decode($verify);
        if ($captcha_success->success==false) {
            //This user was not verified by recaptcha.
            $resultat = 0 ;
        }
        else if ($captcha_success->success==true) {
            $em = $this->getDoctrine()->getManager();

            $contact = new Contact();
            $contact->setEmail($email);
            $contact->setMessage($message);
            $contact->setName($name);
            $contact->setObjet($objet);
            $em->persist($contact);
            $em->flush();
            $resultat = 1 ;
        }

        return new Response($resultat);

    }
   
    /**
     * Page ajax List media vip.
     * @return JsonResponse A JsonResponse instance
     **/
    public function getaleteToDayAction ()
    {
        $em = $this->getDoctrine()->getManager();
        $mediasToDay = $em->getRepository('BackBundle:Medias')->getMediasCourant();
        $alerteRendezvousCourant = $em->getRepository ( 'BackBundle:Rendezvous' )->getAlertRDVVipCourant (   );

        $alerteHtml = $this->renderView('FrontBundle:Ajax:tab2-alerte.html.twig', array('mediasToDay' => $mediasToDay , 'alerteRendezvousCourant' => $alerteRendezvousCourant ));
      
      $notif = 0 ;

      if($mediasToDay != null )
      $notif = count($mediasToDay) ;
      
        return new JsonResponse(array(
            'alerte' => $alerteHtml,
            'notif' =>  $notif
        ));

    }



    /**
     * Page ajax List media vip.
     * @return JsonResponse A JsonResponse instance
     **/
    public function likeMediaAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $medias  = $em->getRepository('BackBundle:Medias')->find($id);

            $item = new LikeMedia();

        $item->setMedia($medias);
        $item->setMember($this->getUser());

        $em->persist($item);
        $em->flush();
        echo 'test';exit();

    }


    /**
     * Page ajax List media vip.
     * @return JsonResponse A JsonResponse instance
     **/
    public function questionAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $valueRadio = $request->get('valueRadio');
        $id = $request->get('id');

        $question  = $em->getRepository('BackBundle:Questions')->find($id);

        $reponse = $em->getRepository('BackBundle:Response')->findOneBy(array('question' => $question));

        if($valueRadio == 1 ){
            $reponse->setOui($reponse->getOui() + 1 );
        }else{
            $reponse->setNon($reponse->getNon() + 1 );
        }

        $em->merge($reponse);
        $em->flush();
        $reponse = $em->getRepository('BackBundle:Response')->findOneBy(array('question' => $question));

        $alerteHtml = $this->renderView('FrontBundle:Ajax:question_pourcentage.html.twig', array('reponse' => $reponse ));



        return new JsonResponse(array(
            'alerteHtml' =>  $alerteHtml
        ));

    }



    /**
     * Page ajax List media vip.
     * @return JsonResponse A JsonResponse instance
     **/
    public function scrollMediaAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $lastId = $request->get('lastId');
        $type = $request->get('type');
        $language = $request->get('language');
        $status = false;
        $tousPeopleHtml = null ;
        if($lastId !=  $this->get('session')->get('lastId') ){
            if($type == 'programme' or $type == 'spectacle' ){
                $tousPeoplescrollInfini = $em->getRepository ( 'BackBundle:Medias' )->getscrollInfiniAjaxProgramme (  $lastId , 8 , $type , $language );
            }else{
                $tousPeoplescrollInfini = $em->getRepository ( 'BackBundle:Medias' )->getscrollInfiniAjax (  $lastId , 8 , $type , $language );
            }


            if($tousPeoplescrollInfini != null ){
                $status = true;
                $tousPeopleHtml = $this->renderView('FrontBundle:Ajax:tous-item.html.twig', array('tousPeoplescrollInfini' => $tousPeoplescrollInfini ));
            }
            $this->get('session')->set('lastId', $lastId);
        }



        return new JsonResponse(array(
            'tousPeopleHtml' => $tousPeopleHtml,
            'status' => $status,

        ));

    }




    /**
     * Page ajax List media vip.
     * @return JsonResponse A JsonResponse instance
     **/
    public function scrollBookImagesAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $lastId = $request->get('lastId');
        $status = false;
        $tousPeopleHtml = null ;
        if($lastId !=  $this->get('session')->get('lastIdBookImagesBD') ){

            $listeBookImagesBDInfini = $em->getRepository ( 'BackBundle:BookImagesBD' )->getscrollInfiniAjax (  $lastId , 8   );



            if($listeBookImagesBDInfini != null ){
                $status = true;
                $tousPeopleHtml = $this->renderView('FrontBundle:Ajax:tous-booklmages.html.twig', array('listeBookImagesBD' => $listeBookImagesBDInfini ));
            }
            $this->get('session')->set('lastIdBookImagesBD', $lastId);
        }



        return new JsonResponse(array(
            'tousPeopleHtml' => $tousPeopleHtml,
            'status' => $status,

        ));

    }



    /**
     * Page partage box
     *
     * @param  Request $request
     * @return JsonResponse A JsonResponse instance
     */

    public function ajaxgetuserAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $search_member = $request->get ( 'search_member' );
        $type_search_member = $request->get ( 'type_search_member' );
        if($type_search_member == "search_member_collection"){
        $listeMembre = $em->getRepository ( 'UserBundle:User' )->searchMembre ( $search_member , "ROLE_MEMBRE" );
        $friendsLlist = $this->renderView ( 'MembreBundle:PartageBox:friends-list.html.twig' , array( 'listeMembre' => $listeMembre ) );

        } else{
        $listeMembre = $em->getRepository ( 'UserBundle:User' )->searchMembre ( $search_member , "ROLE_VIP" );
        $friendsLlist = $this->renderView ( 'FrontBundle:Ajax:Liste_membre_home.html.twig' , array( 'ListeVIP' => $listeMembre ) );
        }

        if($listeMembre != null )
        $countMembre = count($listeMembre);
        else
        $countMembre = 0 ;

        return new JsonResponse( array(
            'friendsLlist' => $friendsLlist,
            'countMembre' => $countMembre
        ) );

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendNewsletterAction(Request $request  ){

        $em = $this->getDoctrine()->getManager();
        $email  = $request->get('newsletter-email') ;
        $stats  = $request->get('stats') ;

        $translator = $this->get('translator');
        ;

        $entity = $em->getRepository('BackBundle:Newsletter')->findOneBy(array('email' => $email ));

        if( $stats == 0 ){

            if($entity != null ){

                $em->remove($entity);
                $em->flush() ;
                $message = $translator->trans('alert.abonnement_supprime')  ;
            }else{
                $message = $translator->trans('alert.abonnement_valide')   ;
            }


        }else{

            if($entity == null ){
                $entity = new Newsletter();
                $entity->setEmail($email) ;
                $em->persist($entity) ;
                $em->flush() ;
                $message = $translator->trans('alert.abonnement_enregistre')  ;
            }else{
                $message =  $translator->trans('alert.abonnement_deja_enregistre') ;
            }

        }


        return new JsonResponse(array(
            'alerte' => $message,

        ));

    }
}
