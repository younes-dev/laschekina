<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Vod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Vod controller.
 *
 */
class VodController extends Controller
{
    /**
     * Lists all vod entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $vods = $em->getRepository('BackBundle:Vod')->findAll();
        $this->get ( 'session' )->set ( 'menuactive' , "vod" );

        return $this->render('BackBundle:vod:index.html.twig', array(
            'vods' => $vods,
        ));
    }

    /**
     * Creates a createOrUpdate vod entity.
     *
     */
    public function createOrUpdateAction(Request $request , $id )
    {
        $em = $this->getDoctrine()->getManager();

        if($id == null ){
            $vod = new Vod();
        }else{
            $vod = $em->getRepository('BackBundle:Vod')->find($id);
        }


        $form = $this->createForm('BackBundle\Form\VodType', $vod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($vod);
            $em->flush();

            $translator = $this->get('translator');
            if($id == null ){
                $this->get('session')->getFlashBag()->add('vodsuccess', $translator->trans('alert.add'));
            }else{
                $this->get('session')->getFlashBag()->add('vodsuccess', $translator->trans('alert.edit'));
            }
            return $this->redirectToRoute('vod_index');
        }

        return $this->render('BackBundle:vod:edit.html.twig', array(
            'id' => $id,
            'vod' => $vod,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a vod entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id-item') ;
         $vod  = $em->getRepository('BackBundle:Vod')->find($id);

        if ($vod != null ) {
            $em->remove($vod);
            $em->flush();
        }

        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('vodsuccess', $translator->trans('alert.delete'));
        return $this->redirectToRoute('vod_index');
    }



}
