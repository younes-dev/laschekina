<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Gallerys;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gallery controller.
 *
 */
class VipController extends Controller
{
    /**
     * Lists all gallery entities.
     *
     */
    public function indexAction ( Request $request  )
    {
        $em = $this->getDoctrine ()->getManager ();
        $this->get ( 'session' )->set ( 'menuactive' , "vip_in_home" );
      
        if ( $request->getMethod () == "POST" ) {
              $listeVips = $request->get('listeVips');
           foreach( $listeVips as $value){
            $user = $em->getRepository('UserBundle:User')->find($value);
           
                $user->setShowInHome(1);
                $em->merge($user);
                $em->flush();
           }
           return $this->redirectToRoute ( 'vip_in_home'  );
  
           }

         
           $users = $em->getRepository('UserBundle:User')->findByRoleHome("ROLE_VIP", 0);
           $usersShowHome = $em->getRepository('UserBundle:User')->findByRoleHome("ROLE_VIP", 1);

        return $this->render ( 'BackBundle:vip:index.html.twig' , array(
            'users' => $users ,
            "usersShowHome" => $usersShowHome
        ) );
    }

   

    /**
     * Deletes a gallery entity.
     *
     */
    public function deleteAction ( $id )
    {
        $em = $this->getDoctrine ()->getManager ();
        $user = $em->getRepository('UserBundle:User')->find($id);
         
        $user->setShowInHome(0);
        $em->merge($user);
        $em->flush();
        return $this->redirectToRoute ( 'vip_in_home'  );
    }


}
