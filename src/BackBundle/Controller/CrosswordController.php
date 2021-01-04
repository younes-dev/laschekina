<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Crossword;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Crossword controller.
 *
 */
class CrosswordController extends Controller
{
    /**
     * Lists all crossword Admin entities.
     *
     */
    public function indexAction ()
    {
        $em = $this->getDoctrine ()->getManager ();
        $this->get ( 'session' )->set ( 'menuactive' , "crosswordadmin" );

        $crosswords = $em->getRepository ( 'BackBundle:Crossword' )->findBy ( array( 'typeuser' => "admin" ) , array( 'id' => 'DESC' ) );
        
        return $this->render ( 'BackBundle:crossword:index.html.twig' , array(
            'crosswords' => $crosswords ,
        ) );
    }

  /**
     * Lists all crossword Admin entities.
     *
     */
    public function ajaxAction (Request $request)
    {
        $magazine_dir = $this->getParameter ( 'magazine_dir' );
        $data = $request->request->get('dateJson');
        $fp = fopen ( "" . $magazine_dir . "dev/magzine/data.json" , "w+" );
        ftruncate ( $fp , 0 );
        
        fwrite ( $fp , $data );
        fclose ( $fp );

        exit();
    }

    /**
     * Lists all crossword Vip entities.
     *
     */
    public function indexvipAction ()
    {
        $em = $this->getDoctrine ()->getManager ();
        $this->get ( 'session' )->set ( 'menuactive' , "crosswordvip" );

        $crosswords = $em->getRepository ( 'BackBundle:Crossword' )->findBy ( array( 'typeuser' => "vip" ) , array( 'id' => 'DESC' ) );
       
        return $this->render ( 'BackBundle:crossword:index.html.twig' , array(
            'crosswords' => $crosswords ,
        ) );
    }


    /**
     * Displays a form to edit an existing crossword entity.
     *
     */
    public function createORupdateAction ( Request $request , $id )
    {

        $em = $this->getDoctrine ()->getManager ();
        if ( $id != null ) {
            $crossword = $em->getRepository ( 'BackBundle:Crossword' )->find ( $id );
        } else {
            $crossword = new Crossword();
        }
        $form = $this->createForm ( 'BackBundle\Form\CrosswordType' , $crossword );
        $form->handleRequest ( $request );

        if ( $form->isSubmitted () && $form->isValid () ) {
            $crossword->setCreator ( $this->getUser () );
            $crossword->setTypeuser ( 'admin' );
            $em->persist ( $crossword );
            $em->flush ( $crossword );
            $crosswords = $em->getRepository ( 'BackBundle:Crossword' )->findBy ( array( 'typeuser' => "admin" , 'statue' =>1 ) , array( 'id' => 'DESC' )  );

            $magazine_dir = $this->getParameter ( 'magazine_dir' );

            $fp = fopen ( "" . $magazine_dir . "dev/magzine/words-clues.js" , "w+" );
            ftruncate ( $fp , 0 );
            $chaine = "var entries = [";
            if ( $crosswords != null ) {
                foreach ( $crosswords as $value ){
                    $chaine .=  "{word:'".$value->getReponse()."', clue:'".$value->getQuestion()."'}," ;
                }
                }
            $chaine .="]";
            fwrite ( $fp , $chaine );
            fclose ( $fp );
            $translator = $this->get ( 'translator' );
            $message = ( $id != null ) ? $translator->trans ( 'alert.edit' ) : $translator->trans ( 'alert.add' );
            $this->get ( 'session' )->getFlashBag ()->add ( 'crosswordsuccess' , $message );


            return $this->redirectToRoute ( 'crossword_index' );
        }

        return $this->render ( 'BackBundle:crossword:edit.html.twig' , array(
            'crossword' => $crossword ,
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
        $crossword = $em->getRepository ( 'BackBundle:Crossword' )->find ( $id );

        if ( $crossword ) {
            $em->remove ( $crossword );
            $em->flush ();
        }
        $crosswords = $em->getRepository ( 'BackBundle:Crossword' )->findBy ( array( 'typeuser' => "admin" , 'statue' =>1 ) , array( 'id' => 'DESC' ) );

        $magazine_dir = $this->getParameter ( 'magazine_dir' );

        $fp = fopen ( "" . $magazine_dir . "dev/magzine/words-clues.js" , "w+" );
        ftruncate ( $fp , 0 );
        $chaine = "var entries = [";
        if ( $crosswords != null ) {
            foreach ( $crosswords as $value ){
                $chaine .=  "{word:'".$value->getReponse()."', clue:'".$value->getQuestion()."'}," ;
            }
        }
        $chaine .="]";
        fwrite ( $fp , $chaine );
        fclose ( $fp );

        $translator = $this->get ( 'translator' );
        $this->get ( 'session' )->getFlashBag ()->add ( 'crosswordsuccess' , $translator->trans ( 'alert.delete' ) );

        return $this->redirectToRoute ( 'crossword_index' );
    }

}
