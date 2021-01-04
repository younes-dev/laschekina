<?php

namespace BackBundle\Controller;

use BackBundle\Entity\TypeContentMedia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Typecontentmedia controller.
 *
 */
class TypeContentMediaController extends Controller
{
    /**
     * Lists all typeContentMedia entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeContentMedia = $em->getRepository('BackBundle:TypeContentMedia')->findAll();
        $this->get('session')->set('menuactive', "typeContentMedia");
        return $this->render('BackBundle:typecontentmedia:index.html.twig', array(
            'typeContentMedia' => $typeContentMedia,
        ));
    }

    /**
     * Creates a new typeContentMedia entity.
     *
     */
    public function createOrUpdateAction(Request $request , $id)
    {
        $em = $this->getDoctrine()->getManager();

        if($id == null )
            $typeContentMedia = new Typecontentmedia();
        else
            $typeContentMedia = $em->getRepository('BackBundle:TypeContentMedia')->find($id);
        $form = $this->createForm('BackBundle\Form\TypeContentMediaType', $typeContentMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $em->persist($typeContentMedia);
            $em->flush();


            $translator = $this->get('translator');

            if($id == null )
                $this->get('session')->getFlashBag()->add('typeContentMediasuccess', $translator->trans('alert.add'));
            else
            $this->get('session')->getFlashBag()->add('typeContentMediasuccess', $translator->trans('alert.edit'));


            return $this->redirectToRoute('typecontentmedia_index');
        }

        return $this->render('BackBundle:typecontentmedia:edit.html.twig', array(
            'typeContentMedia' => $typeContentMedia,
            'id' => $id,
            'form' => $form->createView(),
        ));
    }


    /**
     * Deletes a typeContentMedia entity.
     *
     */
    public function deleteAction(  TypeContentMedia $typeContentMedia)
    {


        if ($typeContentMedia != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeContentMedia);
            $em->flush();
        }

        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('typeContentMediasuccess', $translator->trans('alert.delete'));

        return $this->redirectToRoute('typecontentmedia_index');
    }

}
