<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Newsletter controller.
 *
 */
class NewsletterController extends Controller
{
    /**
     * Lists all newsletter entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $newsletters = $em->getRepository('BackBundle:Newsletter')->findAll();
        $this->get('session')->set('menuactive', "newsletter");

        return $this->render('BackBundle:newsletter:index.html.twig', array(
            'newsletters' => $newsletters,
        ));
    }


    /**
     * Deletes a newsletter entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $newsletter = $em->getRepository('BackBundle:Newsletter')->find($id);

        if ($newsletter) {
            $em->remove($newsletter);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('newslettersuccess', $translator->trans('alert.delete'));



        return $this->redirectToRoute('newsletter_index');
    }


}
