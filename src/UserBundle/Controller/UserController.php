<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('session')->set('menuactive', "admin");
        $users = $em->getRepository('UserBundle:User')->findByRole("ROLE_SUPER_ADMIN");

        return $this->render('UserBundle:User:index.html.twig', array(
            'users' => $users,
            'role' => 'admin',
        ));
    }

 /**
     * Lists all user entities.
     *
     */
    public function gestionRoleAction(Request $request , $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);
        $role = $user->getRoles();

        if($request->getMethod() == 'POST'){

            $this->get('session')->getFlashBag()->add('adminsuccess', "Votre rôle a été changer avec succès");

            $role = $request->get('typecompte');
            $inputFan = $request->get('input_fan');


            if($inputFan == "on"){
                $user->setFan(true);
            }else{
                $user->setFan(false);
            }

            $user->setRoles( array($role));

            $em->merge($user);
            $em->flush();
            $role = $user->getRoles();


            switch ($role[0]) {
                case "ROLE_MEMBRE":
                     $path = 'membre';
                    break;
                case "ROLE_PRO":
                    $path = 'pro';
                    break;
                case "ROLE_VIP":
                    if($user->getFan() == false)
                       $path = 'vip';
                    else
                      $path = 'fan';
                    break;
            }

            if( $role[0] == 'ROLE_SUPER_ADMIN'){

                return $this->redirect($this->generateUrl("user_index"  ));
            }else {

                return $this->redirect($this->generateUrl("user_index_vip" , array('role' => $path)));
            }




        }
        return $this->render('UserBundle:User:gestionRole.html.twig', array(
            'user' => $user,
            'role' => $role[0],
        ));
    }

  /**
     * Lists all user entities vip. 
     *
     */
    public function indexvipAction($role)
    {
        $em = $this->getDoctrine()->getManager();

        if($role == "membre"){
            $this->get('session')->set('menuactive', "membre");
            $users = $em->getRepository('UserBundle:User')->findByRole("ROLE_MEMBRE");
        }elseif($role == "pro"){
            $this->get('session')->set('menuactive', "pro");
            $users = $em->getRepository('UserBundle:User')->findByRole("ROLE_PRO");
        }
        elseif($role == "vip"){
            $this->get('session')->set('menuactive', "vip");
            $users = $em->getRepository('UserBundle:User')->findByRoleVip("ROLE_VIP" , 0 );
        }
         elseif($role == "fan"){
            $this->get('session')->set('menuactive', "fan");
            $users = $em->getRepository('UserBundle:User')->findByRoleVip("ROLE_VIP" , 1 );
        }


        return $this->render('UserBundle:User:index.html.twig', array(
            'users' => $users,
            'role' => $role,
        ));
    }


    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction( $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);

        return $this->render('UserBundle:User:show.html.twig', array(
            'entity' => $user,
        ));
    }



    /**
     * Deletes a user entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);
        $role = $user->getRoles();
        if($role[0] == 'ROLE_SUPER_ADMIN'){
            $path ='user_index';
        }else {
            $path ='user_index_vip';
        }
        if ($user != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush($user);
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('admindanger', $translator->trans('alert.delete'));

            return $this->redirect($this->generateUrl($path));


    }

    public function redirectafterAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();
        $user = $this->getUser();
        $role = $user->getRoles();
        $session = $request->getSession ();
         
       
        if($role[0] == 'ROLE_SUPER_ADMIN'){
            return $this->redirect($this->generateUrl('user_index'));
        }else {
            $session = $request->getSession();

            if($session->get ( 'latitude' ) != null and $session->get ( 'longitude' ) != null ){
            $user->setLatitude ( $session->get ( 'latitude' ) );
            $user->setLongitude ( $session->get ( 'longitude' ) );
            $em->merge ( $user );
            $em->flush ();
            }
          
            $referer = $this->get('session')->get('referer');
            if($referer != null && stristr($referer, 'login') === FALSE)
            return new RedirectResponse($referer );
            else
           return $this->redirect ( $this->generateUrl ( 'front_end_index' , array("page" => $session->get ( 'page' )) ) );
        }


    }

    public function positionajaxAction ( Request $request )
    {

        $session = $request->getSession ();
        $session->set ( 'latitude' , $request->get ( 'latitude' ) );
        $session->set ( 'longitude' , $request->get ( 'longitude' ) );
        
		return new JsonResponse( array (
			'latitude' => $session->get('latitude'),
			'longitude' => $session->get('longitude'),
		));
    }

}
