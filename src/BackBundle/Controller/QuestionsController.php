<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Questions;
use BackBundle\Entity\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Question controller.
 *
 */
class QuestionsController extends Controller
{
    /**
     * Lists all question entities.
     *
     */
    public function indexAction($idMedia)
    {
        $em = $this->getDoctrine()->getManager();

        $questions = $em->getRepository('BackBundle:Questions')->findOneBy(array('media' => $idMedia));
        $reponse = $em->getRepository('BackBundle:Response')->findOneBy(array('question' => $questions));

        return $this->render('BackBundle:questions:index.html.twig', array(
            'questions' => $questions,
            'reponse' => $reponse,
            'idMedia' => $idMedia,
        ));
    }

    /**
     * Creates a new question entity.
     *
     */
    public function createOrUpdateAction(Request $request , $idMedia , $id )
    {
        $em = $this->getDoctrine()->getManager();
        if($id == null )
            $question = new Questions();
        else
            $question = $em->getRepository('BackBundle:Questions')->find($id);

        $form = $this->createForm('BackBundle\Form\QuestionsType', $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $media = $em->getRepository('BackBundle:Medias')->find($idMedia);
            $question->setMedia($media);

            $em->persist($question);
            $em->flush();
            if($id == null ){
                $reponse = new Response();

                $reponse->setQuestion($question);
                $reponse->setNon(0);
                $reponse->setOui(0);
                $em->persist($reponse);
                $em->flush();
            }

            $translator = $this->get('translator');

            if($id == null )
                $this->get('session')->getFlashBag()->add('questionsuccess', $translator->trans('alert.add'));
            else
                $this->get('session')->getFlashBag()->add('questionsuccess', $translator->trans('alert.edit'));



            return $this->redirectToRoute('questions_medias_index',array("idMedia" => $question->getMedia()->getId()));
        }

        return $this->render('BackBundle:questions:edit.html.twig', array(
            'question' => $question,
            'id' => $id,
            'idMedia' => $idMedia,
            'form' => $form->createView(),
        ));
    }


    /**
     * Deletes a question entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $question = $em->getRepository('BackBundle:Questions')->find($id);
        $idMedia = $question->getMedia()->getId();
        if ($question != null ) {
            $em->remove($question);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('questiondelete', $translator->trans('alert.delete'));
        return $this->redirectToRoute('questions_medias_index',array("idMedia" => $idMedia));
    }


}
