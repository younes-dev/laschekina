<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Referancement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Referancement controller.
 *
 */
class ReferancementController extends Controller
{
    /**
     * Lists all referancement entities.
     *
     */
    public function indexAction( $type )
    {
        $em = $this->getDoctrine()->getManager();

        $referancements = $em->getRepository('BackBundle:Referancement')->findBy(array('typePage' => $type ));
        $this->get('session')->set('menuactive', $type );

        return $this->render('BackBundle:referancement:index.html.twig', array(
            'referancements' => $referancements,
        ));
    }

    /**
     * Displays a form to edit an existing referancement entity.
     *
     */
    public function editAction(Request $request, Referancement $referancement)
    {
        $editForm = $this->createForm('BackBundle\Form\ReferancementType', $referancement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('referancementsuccess', $translator->trans('alert.edit'));
           
            return $this->redirectToRoute('referancement_index',array('type' => $referancement->getTypePage() ));
        }

        return $this->render('BackBundle:referancement:edit.html.twig', array(
            'referancement' => $referancement,
            'form' => $editForm->createView(), 
        ));
    }

    
}
