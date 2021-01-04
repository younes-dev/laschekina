<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;

class SecurityController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {

        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;
        $session->set ( 'latitude' , $request->get('latitude') );
        $session->set ( 'longitude' ,  $request->get('longitude')  );
        $referer = $request->headers->get('referer'); 
        $this->get('session')->set('referer',  $referer);

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
           
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;


        /**
         * block de form inscription
         */

        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);


        $em = $this->getDoctrine ()->getManager ();
        $gallerysBanners = $em->getRepository ( 'BackBundle:Collectionmedia' )->findBy(array('type' => 'photos' , 'showPageBanniere' => 1) ,array(), 14);
        $personnageMediasBanners = $em->getRepository ( 'BackBundle:PersonnageMedia' )->findBy(array("showPageBanniere" => 1  ),array("id" => "DESC") );

        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error' => $error,
            'gallerys_banner' => $gallerysBanners,
            'personnageMediasBanners' => $personnageMediasBanners,
            'csrf_token' => $csrfToken,
            'form' => $form->createView(),
        ));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return Response
     */
    protected function renderLogin(array $data)
    {
        return $this->render('UserBundle:Security:login.html.twig', $data);
    }

    public function checkAction()
    {
       
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

  

}
