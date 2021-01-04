<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Rendezvous;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Rendezvous controller.
 *
 */
class RendezvousController extends Controller
{
    /**
     * Lists all rendezvous entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $this->get ( 'session' )->set ( 'menuactive' , "rendezvous" );

        $rendezvouses = $em->getRepository('BackBundle:Rendezvous')->findBy(array(),array('startDate' => "DESC"));

        return $this->render('BackBundle:rendezvous:index.html.twig', array(
            'rendezvouses' => $rendezvouses,
        ));
    }


    /**
     * Displays a form to edit an existing crossword entity.
     *
     */
    public function createORupdateAction ( Request $request , $id )
    {

        $em = $this->getDoctrine ()->getManager ();
        if ( $id != null ) {
            $rendezvous = $em->getRepository ( 'BackBundle:Rendezvous' )->find ( $id );
        } else {
            $rendezvous = new Rendezvous();
        }
        $form = $this->createForm ( 'BackBundle\Form\RendezvousType' , $rendezvous );
        $form->handleRequest ( $request );

        if ( $form->isSubmitted () && $form->isValid () ) {
            $em->persist ( $rendezvous );
            $em->flush (  );
                       $translator = $this->get ( 'translator' );
            $message = ( $id != null ) ? $translator->trans ( 'alert.edit' ) : $translator->trans ( 'alert.add' );
            $this->get ( 'session' )->getFlashBag ()->add ( 'rendezvoussuccess' , $message );


            return $this->redirectToRoute ( 'rendezvous_index' );
        }

        return $this->render('BackBundle:rendezvous:edit.html.twig', array(
            'rendezvous' => $rendezvous,
            'id' => $id ,
            'form' => $form->createView () ,
        ) );
    }

    /**
     * Deletes a radio entity.
     *
     */
    public function deleteAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $crossword = $em->getRepository ( 'BackBundle:Rendezvous' )->find ( $id );

        if ( $crossword ) {
            $em->remove ( $crossword );
            $em->flush ();
        }

        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'rendezvoussuccess' , $translator->trans ( 'alert.delete' ) );

        return $this->redirectToRoute ( 'rendezvous_index' );
    }
}
