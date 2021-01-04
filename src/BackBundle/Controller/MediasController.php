<?php

namespace BackBundle\Controller;

use BackBundle\Entity\ListeDates;
use BackBundle\Entity\Medias;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Media controller.
 *
 */
class MediasController extends Controller
{
    /**
     * Lists all media entities.
     *
     */
    public function indexAction($typemedia, $languge)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('session')->set('menuactive', $typemedia);
        $this->get('session')->set('mediasCourant', $typemedia);
        $this->get('session')->set('languge', $languge);

        $medias = $em->getRepository('BackBundle:Medias')->getMediasListBack($typemedia, $languge);

/*        foreach ($medias as $value){
            $value->setTitleHome(  $value->getTitle());

            $em->merge($value);
            $em->flush();
        }
*/
        return $this->render('BackBundle:Medias:index.html.twig', array(
            'medias' => $medias,
        ));
    }

  /**
     * Lists show media .
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $media = $em->getRepository('BackBundle:Medias')->find($id);

        return $this->render('BackBundle:Medias:show.html.twig', array(
            'media' => $media,
        ));
    }

    /**
     * Creates a new media entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $media = new Medias();
        $media->setUser($user);
        $form = $this->createForm('BackBundle\Form\MediasType', $media);
        $form->handleRequest($request);
        $typemedia = $this->get('session')->get('mediasCourant');
        $languge = $this->get('session')->get('languge');
        if ($form->isSubmitted()) {


            $urlvideo = $form->get('urlvideo')->getData();
            $em = $this->getDoctrine()->getManager();
            $TypeMedia = $em->getRepository('BackBundle:TypeMedia')->findOneBy(array('title' => $typemedia));

           
            $media->setLanguge($languge);
            $media->setTypemedia($TypeMedia);
            $pictureHome = $form->get('pictureHome')->getData();
            if ($pictureHome != null) {
                $picture_Profil = $this->get('app.img_upload')->upload($pictureHome);
                $media->setPictureHome($picture_Profil);
            }
            $picture = $form->get('picture')->getData();
            if ($picture != null) {
                $picture_Profil = $this->get('app.img_upload')->upload($picture);
                $media->setPicture($picture_Profil);
            }
            $backgroundpicture = $form->get('backgroundpicture')->getData();
            if ($backgroundpicture != null) {
                $background_picture = $this->get('app.img_upload')->upload($backgroundpicture);
                $media->setBackgroundpicture($background_picture);
            }
            if($urlvideo != null){
                $video = $this->getIdvideo($urlvideo);
                $media->setSourcevideo($video[0]);
                $media->setUrlvideo($video[1]);
            }

            $location = $request->get('location');
            $lat = $request->get('lat');
            $lng = $request->get('lng');
            $media->setLat($lat);
            $media->setLng($lng);
            $media->setLocation($location);
            $em->persist($media);
            $em->flush($media);

            $startdate = $request->get('startdate');
            $enddate = $request->get('enddate');
            $heurdeb = $request->get('heurdeb');

            if($startdate != null ){

                foreach ($startdate as $key => $value){
                    $item = new ListeDates();
                    $item->setMedia($media);
                    $item->setEndDate($enddate[$key]);
                    $item->setStartDate($startdate[$key]);
                    $item->setStarttime($heurdeb[$key]);

                    $em->persist($item);
                    $em->flush();
                }
            }


            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('mediasuccess', $translator->trans('alert.add'));
            if ( $typemedia !="")
                return $this->redirectToRoute('medias_index', array('typemedia' => $media->getTypemedia()->getTitle(), 'languge' =>  $media->getLanguge()));

                return $this->redirectToRoute('collection', array('activeTab' => "vip",  'fullName' => $this->getUser()->getUsername(),  'id' => $this->getUser()->getId()));
       
            }
        return $this->render('BackBundle:Medias:new.html.twig', array(
            'media' => $media,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing media entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository('BackBundle:Medias')->find($id);
        $old_pictureHome = $media->getPictureHome();
        $old_picture = $media->getPicture();
        $old_Backgroundpicture = $media->getBackgroundpicture();
        $editForm = $this->createForm('BackBundle\Form\MediasType', $media);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $pictureHome = $editForm->get('pictureHome')->getData();

            if ($pictureHome != null) {
                $picture_Profil = $this->get('app.img_upload')->upload($pictureHome);
                $media->setPictureHome($picture_Profil);
                $this->get('app.img_upload')->deleteImage($old_pictureHome);
            } else {
                $media->setPictureHome($old_pictureHome);
            }

            $picture = $editForm->get('picture')->getData();

            if ($picture != null) {
                $picture_Profil = $this->get('app.img_upload')->upload($picture);
                $media->setPicture($picture_Profil);
                $this->get('app.img_upload')->deleteImage($old_picture);
            } else {
                $media->setPicture($old_picture);
            }


             $background_picture = $editForm->get('backgroundpicture')->getData();
            if ($background_picture != null) {
                $background_picture = $this->get('app.img_upload')->upload($background_picture);
                $media->setBackgroundpicture($background_picture);
                $this->get('app.img_upload')->deleteImage($old_Backgroundpicture);
            } else {
                $media->setBackgroundpicture($old_Backgroundpicture);
            }
            
            
            
            $urlvideo = $editForm->get('urlvideo')->getData();

            if($urlvideo != null){
                $video = $this->get ( 'app.img_upload' )->getIdvideo ( $urlvideo ) ;
                $media->setSourcevideo($video[0]);
                $media->setUrlvideo($video[1]);
            }
            $location = $request->get('location');
            $lat = $request->get('lat');
            $lng = $request->get('lng');
            $media->setLat($lat);
            $media->setLng($lng);
            $media->setLocation($location);
            $em->merge($media);
            $em->flush();


            $startdate = $request->get('startdate');
            $enddate = $request->get('enddate');
            $heurdeb = $request->get('heurdeb');
            $heurfin = $request->get('heurfin');

            if($startdate != null ){

                foreach ($startdate as $key => $value){
                    $item = new ListeDates();
                  //  $dateend = date("Y-m-d", strtotime($enddate[$key]));
                 //   $datestart = date("Y-m-d", strtotime($startdate[$key]));



                    list( $day,$month, $year) = explode('/', $enddate[$key] );
                    list( $daystart, $monthstart,$yearstart) = explode('/', $startdate[$key] );

                $newDatestart =  date("$yearstart-$monthstart-$daystart") ;
                $newDateend = date("$year-$month-$day") ;



                    $item->setMedia($media);
                   $item->setEndDate( new \DateTime( $newDateend) );
                      $item->setStartDate( new \DateTime($newDatestart)   );
                      $item->setStarttime($heurdeb[$key]);
                      $item->setEndtime($heurfin[$key]);

                    $em->persist($item);
                     $em->flush();
                }
            }



            $translator = $this->get('translator');

            $this->get('session')->getFlashBag()->add('mediasuccess', $translator->trans('alert.edit'));


                return $this->redirectToRoute('medias_index', array('typemedia' => $media->getTypemedia()->getTitle(), 'languge' =>  $media->getLanguge()));

        }

        return $this->render('BackBundle:Medias:new.html.twig', array(
            'id' => $id,
            'media' => $media,
            'form' => $editForm->createView(),
        ));
    }




    function toJSDate ( $value ) {

        $dateTime = "12/10/2020" ;
        $date = explode("/", $value);

        $newDate = $date[2]."-".$date[1]."-".$date[0];
//(year, month, day, hours, minutes, seconds, milliseconds)
        return new \DateTime($newDate);

    }


    /**
     * Displays a form to edit an existing media entity.
     *
     */
    public function edittAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository('BackBundle:Medias')->find($request->get('id'));
        foreach ($request->get('users') as $user){
            $media->addUser($em->getRepository('UserBundle:User')->find($user));
        }
        foreach ($request->get('personage') as $user){
            $media->addPersonnage($em->getRepository('BackBundle:Personnage')->find($user));
        }
        $typemedia = $this->get('session')->get('mediasCourant');
        $TypeMedia = $em->getRepository('BackBundle:TypeMedia')->findOneBy(array('title' => $typemedia));
        $media->setTypemedia($TypeMedia);
        $media->setTitle($request->get('titre'));
        $media->setUrlradio($request->get('radio'));
        $media->setStarttime($request->get('time'));
        $date = new \DateTime($request->get('date'));
        $media->setStartdate($date);
        $media->setShortdescription($request->get('descriptionbr'));
        $media->setDetaileddescription($request->get('description'));
        $media->setChannel($request->get('channel'));
        $media->setLanguge($request->get('langage'));
        $old_picture = $media->getPicture();
        $old_Backgroundpicture = $media->getBackgroundpicture();
        $picture = $request->get('image');
        if ($picture != null) {
            $picture_Profil = $this->get('app.img_upload')->upload($picture);
            $media->setPicture($picture_Profil);
            $this->get('app.img_upload')->deleteImage($old_picture);
        } else {
            $media->setPicture($old_picture);
        }
        $background_picture = $request->get('imagebg');
        if ($background_picture != null) {
            $background_picture = $this->get('app.img_upload')->upload($background_picture);
            $media->setBackgroundpicture($background_picture);
            $this->get('app.img_upload')->deleteImage($old_Backgroundpicture);
        } else {
            $media->setBackgroundpicture($old_Backgroundpicture);
        }
        $urlvideo = $request->get('youtube');
        if($urlvideo != null){
            $video = $this->get ( 'app.img_upload' )->getIdvideo ( $urlvideo ) ;
            $media->setSourcevideo($video[0]);
            $media->setUrlvideo($video[1]);
        }
        $em->merge($media);
        $em->flush();
    }

    /**
     * Deletes a media entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository('BackBundle:Medias')->find($id);

        if ($media) {
            $em->remove($media);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('mediadelete', $translator->trans('alert.delete'));
        $typemedia = $this->get('session')->get('mediasCourant');
        $languge = $this->get('session')->get('languge');

        return $this->redirectToRoute('medias_index', array('typemedia' => $typemedia, 'languge' => $languge));
    }



    /**
     * Deletes a media entity.
     *
     */
    public function deleteListeDatesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository('BackBundle:ListeDates')->find($id);

        if ($media) {
            $em->remove($media);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('mediadelete', $translator->trans('alert.delete'));
        $typemedia = $this->get('session')->get('mediasCourant');
        $languge = $this->get('session')->get('languge');

        return $this->redirectToRoute('medias_index', array('typemedia' => $typemedia, 'languge' => $languge));
    }


    /**
     * Deletes a commentaire entity.
     *
     */
    public function commentairedeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('BackBundle:CommentaireMedia')->find($id);

        $idMedia = $commentaire->getMedia()->getId();
        if ($commentaire) {
            $em->remove($commentaire);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('commentairedelete', $translator->trans('alert.delete'));
     
 
        return $this->redirectToRoute('medias_show', array('id' => $idMedia));
    }

    /**
     * Deletes a commentaire entity.
     *
     */
    public function dateDeleteAction($id , $idMedia)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('BackBundle:ListeDates')->find($id);

        $media = $em->getRepository('BackBundle:Medias')->find($idMedia);
         if ($commentaire) {
            $em->remove($commentaire);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('datedelete', $translator->trans('alert.delete'));

        return $this->redirectToRoute('medias_index', array('typemedia' => $media->getTypemedia()->getTitle(), 'languge' =>  $media->getLanguge()));


    }


    /**
     * Deletes a commentaire entity.
     *
     */
    public function dateDeleteEditAction($id , $idMedia)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('BackBundle:ListeDates')->find($id);

        $media = $em->getRepository('BackBundle:Medias')->find($idMedia);
         if ($commentaire) {
            $em->remove($commentaire);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('datedeleteEdit', $translator->trans('alert.delete'));

        return $this->redirectToRoute('medias_edit', array('id' => $media->getId()));


    }

    /**
     * @param $url
     * @return mixed
     */
public function getIdvideo($url){
    //initialisation des variables
    $host = '';
    $id = '';
    $formated_url = '';

    //On détermine où est hebergée la vidéo (youtube, dailymotion, vimeo) et on extrait les données nécessaires au formatage du lien shadowbox
    $parse = parse_url($url);

    switch ($parse['host']) {
        case 'youtu.be':
            $host = 'youtube';
            $id = substr($parse['path'], 1);
            break;
        case 'www.youtube.com':
            $host = 'youtube';
            $parse2 = parse_str($parse['query'], $data);
            $id = $data['v'];
            break;
        case 'vimeo.com':
            $host = 'vimeo';
            $id = substr($parse['path'], 1);
            break;
        case 'www.dailymotion.com':
            $host = 'dailymotion';
            $id = substr($parse['path'], 7);
            break;

        default:
            break;
    }

    //On formate le lien selon l'hébergeur
    switch ($host) {
        case 'youtube':
            $formated_url = "https://www.youtube.com/watch?v=".$id."?autoplay=1";

            break;
        case 'vimeo':
            $formated_url =  "https://player.vimeo.com/video/".$id."?color=ffffff&title=0&byline=0&portrait=0?autoPlay=1" ;

            break;
        case 'dailymotion':
            $formated_url =  "http://www.dailymotion.com/embed/video/".$id."?autoPlay=1";

            break;

        default:
            break;
    }

    $coordonnses_video[0] = $host;
    $coordonnses_video[1] = $formated_url;
    return $coordonnses_video;
}
}
