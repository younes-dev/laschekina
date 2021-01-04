<?php

namespace Corona\EndBundle\Controller;

use Corona\EndBundle\Entity\Questions;
use Corona\EndBundle\Entity\Response;
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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $questions = $em->getRepository('CoronaEndBundle:Questions')->findAll();
        $this->get('session')->set('menuactive', "questions");
        return $this->render('CoronaEndBundle:questions:index.html.twig', array(
            'questions' => $questions,
        ));
    }

    /**
     * Creates a new question entity.
     *
     */
    public function createOrUpdateAction(Request $request , $id )
    {

        $em = $this->getDoctrine()->getManager();

        if($id == null )
            $question = new Questions();
        else
            $question = $em->getRepository('CoronaEndBundle:Questions')->find($id);
        $form = $this->createForm('Corona\EndBundle\Form\QuestionsType', $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($question);
            $em->flush();

            $listeReposne = $request->get('reponse');
            $kg = $request->get('kg');

            if($listeReposne != null ){
                foreach ($listeReposne as $key=>$value){
                    $item = new Response();
                    $item->setTitle($value);
                    $item->setNbrKg($kg[$key]);
                    $item->setQuestion($question);
                    $em->persist($item);
                    $em->flush();
                }
            }
            return $this->redirectToRoute('questions_index' );
        }

        return $this->render('CoronaEndBundle:questions:edit.html.twig', array(
            'question' => $question,
            'id' => $id,
            'form' => $form->createView(),
        ));
    }



    /**
     * Deletes a question entity.
     *
     */
    public function deleteResponseAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $Response = $em->getRepository('CoronaEndBundle:Response')->find($id);

        if ($Response != null ) {
            $em->remove($Response);
            $em->flush();
        }

        return $this->redirectToRoute('questions_index');
    }

    /**
     * Deletes a question entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $question = $em->getRepository('CoronaEndBundle:Questions')->find($id);

        if ($question != null ) {
            $em->remove($question);
            $em->flush();
        }

        return $this->redirectToRoute('questions_index');
    }


}
