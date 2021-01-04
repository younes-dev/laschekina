<?php

namespace MembreBundle\Controller;


use BackBundle\Entity\Messaging;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontMessagingController extends Controller
{


    /**************  Liste des fonctions de Messaging ******************/
    /**
     *  Liste Messaging  de message received
     *
     * @return Response A Response instance
     **/
    public function pagereceivedAction ()
    {

        $this->get ( 'session' )->set ( 'activerouteCourant' , '' );
        $em = $this->getDoctrine ()->getManager ();
        $userCourant = $this->getUser ();
        $ListeMessage = $em->getRepository ( 'BackBundle:Messaging' )->findBy ( array( "userreceiver" => $userCourant->getId () , "deleteenable" => 0 ) , array( "enable" => "ASC" , "dateofsending" => "DESC" ) );
        $numbreMessageNotSeen = $em->getRepository ( 'BackBundle:Messaging' )->CountMessageNotSeen ( $userCourant->getId () , 0 );

        return $this->render ( 'MembreBundle:Messaging:received.html.twig' , array(
            "numbreMessageNotSeen" => $numbreMessageNotSeen ,
            "ListeMessage" => $ListeMessage ,
        ) );
    }


    /**
     *  Liste Messaging page  de message remove
     * @return Response A Response instance
     **/
    public function pageremoveAction ()
    {
        $this->get ( 'session' )->set ( 'activerouteCourant' , '' );
        $em = $this->getDoctrine ()->getManager ();
        $userCourant = $this->getUser ();
        $ListeMessage = $em->getRepository ( 'BackBundle:Messaging' )->ListeMessageRemove ( $userCourant->getId () );

        return $this->render ( 'MembreBundle:Messaging:remove.html.twig' , array(
            "ListeMessage" => $ListeMessage ,
        ) );
    }

    /**
     *  Liste Messaging page de message send
     * @param Request $request The current request.
     * @return Response A Response instance
     **/
    public function pagesendAction ()
    {
        $this->get ( 'session' )->set ( 'activerouteCourant' , '' );
        $em = $this->getDoctrine ()->getManager ();
        $userCourant = $this->getUser ();
        $ListeMessage = $em->getRepository ( 'BackBundle:Messaging' )->findBy ( array( "useremitter" => $userCourant->getId () , "deleteenable" => 0 ) , array( "enable" => "ASC" , "dateofsending" => "DESC" ) );
        return $this->render ( 'MembreBundle:Messaging:send.html.twig' , array(
            "ListeMessage" => $ListeMessage ,
        ) );
    }

    /**
     * Page page de formulaire d'envoie
     * @return Response
     **/
    public function pageenvoieAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();
      $userreceiver =     $id != null ? $em->getRepository('UserBundle:User')->find($id) :    $userreceiver =   $em->getRepository('BackBundle:Listamis')->ListCoupleAmis($this->getUser ()->getId()) ;

     //   $userreceiver =   $em->getRepository('BackBundle:Listamis')->ListCoupleAmis($this->getUser ()->getId());
         return $this->render ( 'MembreBundle:Messaging:new.html.twig',array(
           "useremitter" => $this->getUser () ,
           "userreceiver" => $userreceiver,
           "id" => $id
        ) );

    }


    /**
     * Page ajax change state.
     * @return JsonResponse A JsonResponse instance
     **/
    public function changestateAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $id = $request->get ( 'id' );
        $message = $em->getRepository ( 'BackBundle:Messaging' )->find ( $id );
        $message->setEnable ( 1 );
        $em->merge ( $message );
        $em->flush ();
        $userCourant = $this->getUser ();
        $numbreMessageNotSeen = $em->getRepository ( 'BackBundle:Messaging' )->CountMessageNotSeen ( $userCourant->getId () , 0 );

        return new JsonResponse( array(
            'nbrmessage' => $numbreMessageNotSeen ,
        ) );

    }

    /**
     * Page ajax change state.
     * @param Request $request The current request.
     * @return JsonResponse A JsonResponse instance
     **/
    public function deleteAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $id = $request->get ( 'id-item' );
        $rout = $request->get ( 'rout' );
        $message = $em->getRepository ( 'BackBundle:Messaging' )->find ( $id );
        $message->setDeleteenable ( 1 );
        $em->merge ( $message );
        $em->flush ();
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'messagingalerte' , $translator->trans ( 'alert.delete' ) );

        return $this->redirectToRoute ( $rout );


    }

    /**
     * function permete d'envoie de message
     * @param Request $request The current request.
     * @return JsonResponse A JsonResponse instance
     **/
    public function msgenvoyeAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $useremitter = $this->getUser ();
        $idUserReceiver = $request->get ( 'id-userreceiver' );
        $message = $request->get ( 'message' );
        $rout = $request->get ( 'rout' );
        $userreceiver = $em->getRepository ( 'UserBundle:User' )->find ( $idUserReceiver );
        $messaging = new Messaging();
        $messaging->setUseremitter ( $useremitter );
        $messaging->setUserreceiver ( $userreceiver );
        $messaging->setMessage ( $message );
        $em->persist ( $messaging );
        $em->flush ();
		
		
        $bodyMessage = $this->renderView ( 'MembreBundle:Messaging:messageEmail.html.twig' , array(
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
        $this->get ( 'session' )->getFlashBag ()->add ( 'messagingalerte' , $translator->trans ( 'alert.add' ) );
        if ( $rout == "received" )
            return $this->redirectToRoute ( "front_messaging_received" );
        else
            return $this->redirectToRoute ( "front_messaging_send" );


    }
}
