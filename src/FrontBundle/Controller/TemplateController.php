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
class TemplateController extends Controller
{
    /**
     * Home page.
     *
     * @param Request $request The current request.
     *
     * @return Response A Response instance
     **/
    public function pubAction ( Request $request   )
    {

        $em = $this->getDoctrine ()->getManager ();
        $information = $em->getRepository ( 'BackBundle:Information' )->findOneBy ( array() , array() , 1 );


        return $this->render ( 'FrontBundle:Template:pub.html.twig' , array(
            "information" => $information

        ) );
    }

    /** function access denied **/
    public function accessDeniedAction()
    {
        return $this->render('FrontBundle:Template:access_denied.html.twig');
    }

}
