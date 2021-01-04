<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Information;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Information controller.
 *
 */
class InformationController extends Controller
{
    /**
     * Lists all information entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('session')->set('menuactive', 'information');

        $information = $em->getRepository('BackBundle:Information')->findAll();

        return $this->render('BackBundle:information:index.html.twig', array(
            'information' => $information,
        ));
    }
    /**
     * Displays a form to edit an existing information entity.
     *
     */
    public function editAction(Request $request, Information $information)
    {
        $editForm = $this->createForm('BackBundle\Form\InformationType', $information);
        $editForm->handleRequest($request);
        $old_Backgroundpicture = $information->getBackgroundpicture();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $background_picture = $editForm->get('backgroundpicture')->getData();
            if ($background_picture != null) {
                $background_picture = $this->get('app.img_upload')->upload($background_picture);
                $information->setBackgroundpicture($background_picture);
                $this->get('app.img_upload')->deleteImage($old_Backgroundpicture);
            } else {
                $information->setBackgroundpicture($old_Backgroundpicture);
            }
            $this->getDoctrine()->getManager()->flush();
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('informationsuccess', $translator->trans('alert.edit'));

            return $this->redirectToRoute('information_index');
        }

        return $this->render('BackBundle:information:edit.html.twig', array(
            'information' => $information,
            'edit_form' => $editForm->createView(),
        ));
    }

  }
