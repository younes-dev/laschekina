<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Activity controller.
 *
 */
class ActivityController extends Controller
{
    /**
     * Lists all activity entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $activities = $em->getRepository('BackBundle:Activity')->findBy(array(), array('id' => "DESC"));
        $this->get('session')->set('menuactive', "activity");

        return $this->render('BackBundle:activity:index.html.twig', array(
            'activities' => $activities,
        ));
    }

    /**
     * Creates a new activity entity.
     *
     */
    public function newAction(Request $request)
    {
        $activity = new Activity();
        $form = $this->createForm('BackBundle\Form\ActivityType', $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('activitysuccess', $translator->trans('alert.add'));
            return $this->redirectToRoute('activity_index');
        }

        return $this->render('BackBundle:activity:edit.html.twig', array(
            'activity' => $activity,
            'form' => $form->createView(),
        ));
    }



    /**
     * Displays a form to edit an existing activity entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $activity = $em->getRepository('BackBundle:Activity')->find($id);
        $editForm = $this->createForm('BackBundle\Form\ActivityType', $activity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('activitysuccess', $translator->trans('alert.edit'));

            return $this->redirectToRoute('activity_index');
        }
        return $this->render('BackBundle:activity:edit.html.twig', array(
            'id' => $id,
            'activity' => $activity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a activity entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $activity = $em->getRepository('BackBundle:Activity')->find($id);

        if ($activity != null) {
            $em->remove($activity);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('activitydelete', $translator->trans('alert.delete'));

        return $this->redirectToRoute('activity_index');
    }



}
