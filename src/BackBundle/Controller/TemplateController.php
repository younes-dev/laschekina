<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Radio controller.
 *
 */
class TemplateController extends Controller
{
    /**
     * alert Message
     *
     */
    public function alertMessageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository ( 'BackBundle:Contact' )->findBy ( array( "enable" => 0 ) , array( "dateenvoie" => "DESC" ) );

        return $this->render('BackBundle:Template:alert.html.twig', array(
            'contacts' => $contacts,
        ));
    }


}
