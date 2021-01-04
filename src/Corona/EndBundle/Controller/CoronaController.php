<?php

namespace Corona\EndBundle\Controller;

use Corona\EndBundle\Entity\Questions;
use Corona\EndBundle\Entity\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Question controller.
 *
 */
class CoronaController extends Controller
{
    /**
     * Lists all question entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $nbrCorona = $em->getRepository('CoronaEndBundle:NbrCorona')->findOneBy(array());

       $nbr =  $nbrCorona->getNbr() + 1 ;

        $nbrCorona->setNbr($nbr);
        $em->persist($nbrCorona);
        $em->flush();
        $questions = $em->getRepository('CoronaEndBundle:Questions')->findAll();
        $session = $request->getSession ();
        $session->set ( 'activerouteCourant' , "corona" );

        return $this->render('CoronaEndBundle:front:index.html.twig', array(
            'questions' => $questions,
        ));
    }


  /**
     * Lists all question entities.
     *
     */
    public function inviterAmiAction(Request $request)
    {

        $email = $request->get('email');
        $name = $request->get('name');
        $to = $email;
        $subject = "Invitation pour simuler votre poids après le confinement";
        $message = $this->renderView('CoronaEndBundle:front:email.html.twig' , array('name' => $name));

        $headers = 'From: info@vipcrossing.com' . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

        mail($to, $subject, $message, $headers);

        return new JsonResponse(array(
            'alerte' => "Votre message a bien été envoyé à notre ami(e)  ". $email,

        ));
    }


}
