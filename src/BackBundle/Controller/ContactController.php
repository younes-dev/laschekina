<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contact controller.
 *
 */
class ContactController extends Controller
{
    /**
     * Lists all contact entities.
     *
     */
    public function indexAction ( $enable )
    {
        $em = $this->getDoctrine ()->getManager ();
        $menuActive = "contact_index_" . $enable;
        $this->get ( 'session' )->set ( 'menuactive' , $menuActive );
        $contacts = $em->getRepository ( 'BackBundle:Contact' )->findBy ( array( "enable" => $enable ) , array( "dateenvoie" => "DESC" ) );

        return $this->render ( 'BackBundle:contact:index.html.twig' , array(
            'contacts' => $contacts ,
            'enable' => $enable ,
        ) );
    }


    /**
     * Finds and displays a contact entity.
     *
     */
    public function showAction ( $id )
    {

        $em = $this->getDoctrine ()->getManager ();
        $contact = $em->getRepository ( 'BackBundle:Contact' )->find ( $id );
        $contact->setEnable(1);
        $em->merge($contact);
        $em->flush ();
        return $this->render ( 'BackBundle:contact:show.html.twig' , array(
            'contact' => $contact ,
        ) );
    }


    /**
     * Deletes a contact entity.
     *
     */
    public function deleteAction ( $id )
    {

        $em = $this->getDoctrine ()->getManager ();
        $contact = $em->getRepository ( 'BackBundle:Contact' )->find ( $id );
        if ( $contact->getEnable () == 1 )
            $enable = 1;
        else
            $enable = 0;
        if ( $contact ) {
            $em->remove ( $contact );
            $em->flush ();

        }
        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'contactsuccess' , $translator->trans ( 'alert.delete' ) );

        return $this->redirectToRoute ( 'contact_index' , array( "enable" => $enable ) );

    }


}
