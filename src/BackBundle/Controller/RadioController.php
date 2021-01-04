<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Radio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Radio controller.
 *
 */
class RadioController extends Controller
{
    /**
     * Lists all radio entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $radios = $em->getRepository('BackBundle:Radio')->findAll();
        $this->get('session')->set('menuactive', "chaine_radio");

        return $this->render('BackBundle:Radio:index.html.twig', array(
            'radios' => $radios,
        ));
    }

    /**
     * Creates a new radio entity.
     *
     */
    public function newAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $radio = new Radio();
        $form = $this->createForm('BackBundle\Form\RadioType', $radio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('path')->getData();
            if ($picture != null) {
                $name_picture = $this->get('app.img_upload')->upload($picture);
                $radio->setPath($name_picture);
            }
            $em->persist($radio);
            $em->flush();
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('radiosuccess', $translator->trans('alert.add'));

            return $this->redirectToRoute('radio_index');
        }

        return $this->render('BackBundle:Radio:new.html.twig', array(
            'radio' => $radio,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing radio entity.
     *
     */
    public function editAction(Request $request,  $id)
    {

        $em = $this->getDoctrine()->getManager();
        $radio = $em->getRepository('BackBundle:Medias')->find($id);
        $old_picture = $radio->getPath();
        $editForm = $this->createForm('BackBundle\Form\RadioType', $radio);
        $editForm->handleRequest($request);
     
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $picture = $editForm->get('path')->getData();
            if ($picture != null) {
                $name_picture = $this->get('app.img_upload')->upload($picture);
                $radio->setPath($name_picture);
                $this->get('app.img_upload')->deleteImage($old_picture);
            } else {
                $radio->setPath($old_picture);
            }
            $em->merge($radio);
            $em->flush();
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('radiosuccess', $translator->trans('alert.edit'));

            return $this->redirectToRoute('radio_index');
        }

        return $this->render('BackBundle:Radio:new.html.twig', array(
            'radio' => $radio,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a radio entity.
     *
     */
    public function deleteAction( $id)
    {
        $em = $this->getDoctrine()->getManager();
        $radio = $em->getRepository('BackBundle:Radio')->find($id);

        if ($radio) {
            $em->remove($radio);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('radiodelete', $translator->trans('alert.delete'));

        return $this->redirectToRoute('radio_index');
    }

  
}
