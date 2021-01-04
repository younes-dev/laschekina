<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Personnage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Personnage controller.
 *
 */
class PersonnageController extends Controller
{
    /**
     * Lists all personnage entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $personnages = $em->getRepository('BackBundle:Personnage')->findBy(array(),array('id' => 'DESC'));
        $this->get('session')->set('menuactive', "personnage");

        return $this->render('BackBundle:Personnage:index.html.twig', array(
            'personnages' => $personnages,
        ));
    }

    /**
     * Creates a new personnage entity.
     *
     */
    public function newAction(Request $request)
    {
        $personnage = new Personnage();
        $form = $this->createForm('BackBundle\Form\PersonnageType', $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $picture = $form->get('image')->getData();
            if ($picture != null ) {
                $picture = $this->get('app.img_upload')->upload($picture);
                $personnage->setImage($picture);
            }
            $em->persist($personnage);
            $em->flush($personnage);
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('personnagesuccess', $translator->trans('alert.add'));

            return $this->redirectToRoute('personnage_index');
        }

        return $this->render('BackBundle:Personnage:edit.html.twig', array(
            'personnage' => $personnage,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing personnage entity.
     *
     */
    public function editAction(Request $request,  $id )
    {
        $em = $this->getDoctrine()->getManager();
        $personnage = $em->getRepository('BackBundle:Personnage')->find($id);
        $old_picture = $personnage->getImage();
        $editForm = $this->createForm('BackBundle\Form\PersonnageType', $personnage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $picture = $editForm->get('image')->getData();
            if ($picture != null) {
                $picture = $this->get('app.img_upload')->upload($picture);
                $personnage->setImage($picture);
                $this->get('app.img_upload')->deleteImage($old_picture);

            } else {
                $personnage->setImage($old_picture);
            }
            $this->getDoctrine()->getManager()->flush();

            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('personnagesuccess', $translator->trans('alert.edit'));
            return $this->redirectToRoute('personnage_index');
        }

        return $this->render('BackBundle:Personnage:edit.html.twig', array(
            'id' => $id,
            'personnage' => $personnage,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a personnage entity.
     *
     */
    public function deleteAction(  $id)
    {

        $em = $this->getDoctrine()->getManager();
        $personnage = $em->getRepository('BackBundle:Personnage')->find($id);

        if ($personnage != null) {
            $em->remove($personnage);
            $em->flush($personnage);
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('personnagedelete', $translator->trans('alert.delete'));

        return $this->redirectToRoute('personnage_index');
    }


}
