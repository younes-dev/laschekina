<?php

namespace MembreBundle\Controller;

use BackBundle\Entity\BookImagesBD;
use BackBundle\Entity\BookVotes;
use BackBundle\Entity\Box;
use BackBundle\Entity\Collectionmedia;
use BackBundle\Entity\Humeur;
use BackBundle\Entity\Listamis;
use BackBundle\Entity\Magazinegallery;
use BackBundle\Entity\Medias;
use BackBundle\Entity\MembreVuCollection;
use BackBundle\Entity\Messaging;
use BackBundle\Entity\Personnage;
use BackBundle\Entity\PersonnageMedia;
use BackBundle\Entity\PersonnageVote;
use BackBundle\Entity\Rendezvous;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FrontMembreController extends Controller
{


    /**************  Liste des fonctions de partie collection ******************/

    /**
     *  Page Collection
     * @param Request $request The current request.
     * @param string $id The user identifier.
     *
     * @return Response A Response instance
     **/
    public function collectionAction($activeTab, $fullName, $id)
    {

        $this->get('session')->set('activerouteCourant', '');
        $em = $this->getDoctrine()->getManager();
        $media = new Medias();
        $metaDescription = null ;
        $form = $this->createForm('BackBundle\Form\MediasType', $media);
        $editForm = $this->createForm('BackBundle\Form\MediasType', $media);
        $typemedia = $em->getRepository('BackBundle:TypeMedia')->findAll();
        $repository = $this->getDoctrine()
            ->getRepository('UserBundle:User');
        $query = $repository->createQueryBuilder('u')
            ->orderBy("u.username", 'ASC')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"ROLE_VIP"%')->getQuery();
        $users = $query->getResult();
        $repositoryy = $this->getDoctrine()
            ->getRepository(Personnage::class);
        $que = $repositoryy->createQueryBuilder('c')
            ->orderBy("c.id", 'DESC')->getQuery();
        $personage = $que->getResult();
        $evants = array();
        if ($this->getUser())
            $evants = $em->getRepository('BackBundle:Medias')->findBy(['user' => $this->getUser()->getId()]);

        $member = $em->getRepository('UserBundle:User')->find($id);
        if($this->getUser() != null ){
            $membreVuCollection = $em->getRepository('BackBundle:MembreVuCollection')->findOneBy(array( "memberEmitter"=> $id , "memberCollection" =>$this->getUser()->getId() )    );

            if($membreVuCollection != null ){


                $membreVuCollection->setCollectionPhoto(true);
                $membreVuCollection->setCollectionNews(true) ;
                $membreVuCollection->setCollectionMedia(true) ;

                $em->merge($membreVuCollection);
                $em->flush();
            }

        }



        $ListFans = $em->getRepository('BackBundle:Box')->getfansBox($id);

        $userCourant = $this->getUser();
        $User_in_box = null;
        $amis = null;
        $ListFanszen = null;
        $colonne_type = null;
        $listIdBook = null;
        $ListPictureHumeur = null;
        $listMagazinegalleries = null;
        if ($userCourant == null or $userCourant->getId() != $id) {
            if ($member->getNumbervu() != null) {
                $nmbr = $member->getNumbervu() + 1;
            } else {
                $nmbr = 1;
            }

            $member->setNumbervu($nmbr);
            $em->merge($member);
            $em->flush();
        }
        if ($userCourant != null) {
            $User_in_box = $em->getRepository('BackBundle:Box')->findOneBy(array("vip" => $member->getId(), "membre" => $userCourant->getId()));

            $findListeAmis = $em->getRepository('BackBundle:Listamis')->findListeAmis($id, $userCourant->getId());
            $amis = ($findListeAmis != null) ? $findListeAmis[0] : null;
            $listMagazinegalleries = $em->getRepository('BackBundle:Magazinegallery')->findBy(array("typegallerys" => "vip", "usercreate" => $userCourant->getId()), array("state" => "DESC"));

            $ListPictureHumeur = $em->getRepository('BackBundle:Humeur')->findBy(array("member" => $id));
            $colonne_type = $em->getRepository('BackBundle:Humeur')->getTypeHumeur($id);
            $colonne_type = array_column($colonne_type, 'type');
            $listIdBook = $em->getRepository('BackBundle:Payments')->getIdBook($userCourant);
            if ($listIdBook != null)
                $listIdBook = array_column($listIdBook, 'id');
        }

        $Liste_Photos = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMedia("photos", $id);
        $Liste_Videos = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMedia("videos", $id);
        $liste_mp3 = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByType("videos", $id, 'mp3');
        $liste_mp3_enfant = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByType("videos", $id, 'mp3_enfant');
        $liste_contes_histoires_enfants = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByType("videos", $id, 'contes_histoires_enfants');
        $liste_contes_histoires = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByType("videos", $id, 'contes_histoires');
        $liste_mp4 = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByType("videos", $id, 'mp4');
        $liste_url = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByType("videos", $id, 'Url');
        $listeVideoYoutoubeMp4 = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByTypeNnbrFix("videos", $id, 'Url',  6);
        $listeBookBD = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByTypeBD("videos", $id, 'liver_bd');
        $listeBook = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaByType("videos", $id, 'book');
        $ListeDescriptionBook = null;
        if (count($listeBook) > 0) {
            $i = 1;
            foreach ($listeBook as $value) {
                $description = html_entity_decode($value['description']);
                $ListeDescriptionBook[$i] = substr($description, 0, 300);
                $i++;
            }
        }
        $Liste_News = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMedia("news", $id);
        $Liste_Rendezvous = $em->getRepository('BackBundle:Rendezvous')->findBy(array("vip" => $id), array("startDate" => "DESC"));
        $Liste_Rendezvous_map = $em->getRepository('BackBundle:Rendezvous')->findRDVMap($id);
        $getListeacteur = $em->getRepository('BackBundle:Medias')->getListeacteur($id);
        
        $Liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);



        $ListCoupleAmis = $em->getRepository('BackBundle:Listamis')->ListCoupleAmis($id);




        $ListFans = $em->getRepository('BackBundle:Box')->getfansBox($id);

        $ListFanszen = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "zen");
        $ListFansgaite = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "gaite");
        $ListFansdeprime = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "deprime");
        $ListFanscolere = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "colere");
        $ListFansfatigue = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "fatigue");
        $ListFansenergique = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "energique");
        $ListFansAutherHumeur = $em->getRepository('BackBundle:Box')->getfansAutherHumeurBox($id, "energique");

    $ListFansenergique = array_merge($ListFansenergique, $ListFansAutherHumeur);

          $PictureCourantHumeur = $em->getRepository('BackBundle:Humeur')->findOneBy(array("member" => $member->getId(), "type" => $member->getColoremotioncard()));

        $entitiesTotla = $em->getRepository('BackBundle:Box')->findBy(array("vip" => $id, "typebox" => "user"));

        if ($entitiesTotla != null)
            $totla = count($entitiesTotla);
        else
            $totla = 0;

        $ListFanszenTotal = $em->getRepository('BackBundle:Box')->getfansHumeurAll("zen");
        $ListFansgaiteTotal = $em->getRepository('BackBundle:Box')->getfansHumeurAll( "gaite");
        $ListFansdeprimeTotal = $em->getRepository('BackBundle:Box')->getfansHumeurAll( "deprime");
        $ListFanscolereTotal = $em->getRepository('BackBundle:Box')->getfansHumeurAll( "colere");
        $ListFansfatigueTotal = $em->getRepository('BackBundle:Box')->getfansHumeurAll( "fatigue");
        $ListFansenergiqueTotal = $em->getRepository('BackBundle:Box')->getfansHumeurAll( "energique");


        $totlaTotal = count ( $ListFanszenTotal ) + count ( $ListFansgaiteTotal ) + count ( $ListFansdeprimeTotal ) + count ( $ListFanscolereTotal ) + count ( $ListFansfatigueTotal ) + count ( $ListFansenergiqueTotal );
       if($member->getDescription() != null )
        $metaDescription = html_entity_decode($member->getDescription());
        
        return $this->render('MembreBundle:Collection:index.html.twig', array(
            'getListeacteur'=> $getListeacteur,
            'metaDescription' => $metaDescription  ,
            'ListFanszenTotal' => $ListFanszenTotal,
            'ListFansgaiteTotal' => $ListFansgaiteTotal,
            'ListFansdeprimeTotal' => $ListFansdeprimeTotal,
            'ListFanscolereTotal' => $ListFanscolereTotal,
            'ListFansfatigueTotal' => $ListFansfatigueTotal,
            'ListFansenergiqueTotal' => $ListFansenergiqueTotal,

            'totlaTotal' => $totlaTotal ,

            "id" => $id,
             'ListPictureHumeur' => $ListPictureHumeur,
            'liste_mp3' => $liste_mp3,
            'listeMp3Enfant' => $liste_mp3_enfant,
            'listeContesHistoiresEnfants' => $liste_contes_histoires_enfants,
            'listeContesHistoires' => $liste_contes_histoires,
            'listeVideoYoutoubeMp4' => $listeVideoYoutoubeMp4,
            'liste_mp4' => $liste_mp4,
            'liste_url' => $liste_url,
            'listeBook' => $listeBook,
            'listeBookBD' => $listeBookBD,
            'ListeDescriptionBook' => $ListeDescriptionBook,

            'totla' => $totla,
            'userInBox' => $User_in_box,
            'listeVIP' => $Liste_VIP,
            'member' => $member,
            'activeTab' => $activeTab,
            'listePhotos' => $Liste_Photos,
            'listeVideos' => $Liste_Videos,
            'listeNews' => $Liste_News,
            'amis' => $amis,
            'listCoupleAmis' => $ListCoupleAmis,
            'listMagazinegalleries' => $listMagazinegalleries,
            'ListFans' => $ListFans,
            'ListFanszen' => $ListFanszen,
            'ListFansgaite' => $ListFansgaite,
            'ListFansdeprime' => $ListFansdeprime,
            'ListFanscolere' => $ListFanscolere,
            'ListFansfatigue' => $ListFansfatigue,
            'ListFansenergique' => $ListFansenergique,
            'ListeRendezvous' => $Liste_Rendezvous,
            'ListeRendezvousMap' => $Liste_Rendezvous_map,
            'colonneType' => $colonne_type,
            'pictureCourantHumeur' => $PictureCourantHumeur,
            'listIdBook' => $listIdBook,
            'media' => $media,
            'form' => $form->createView(),
            'editform' => $editForm->createView(),
            'typemedias' => $typemedia,
            'evants' => $evants,
            'users' => $users,
            'personage' => $personage,
        ));
    }


    /**
     *  Page homePageShowVip
     *
     * @return Response A Response instance
     **/
    public function homePageShowVipAction()
    {

        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');
        $member = $this->getUser();
        if ($member->getShowVipBoxInHome() == 1) {
            $member->setShowVipBoxInHome(0);
            $this->get('session')->getFlashBag()->add('showVipHome', $translator->trans('alert.show_vip_box'));

        } else {
            $member->setShowVipBoxInHome(1);
            $this->get('session')->getFlashBag()->add('showVipHome', $translator->trans('alert.show_vip_default'));

        }

        $em->merge($member);
        $em->flush();

        return $this->redirect($this->generateUrl('front_end_home'));


    }


    /**************  Liste des fonctions de la page Crossing Map ******************/

    /**
     *  Page Collection
     * @param Request $request The current request.
     * @param string $id The user identifier.
     *
     * @return Response A Response instance
     **/
    public function addRendervouscollectionAction(Request $request, $activeTab)
    {

        $em = $this->getDoctrine()->getManager();
        $member = $this->getUser();
        $rendezVous = new Rendezvous();
        $media = $request->get('media');
        $title = $request->get('title_rendez_vous');
        $startdate = $request->get('startdate_rendez_vous');
        $enddate = $request->get('enddate_rendez_vous');


        $endHeur = $request->get('end_heur');
        $startHeur = $request->get('start_heur');


        $location = $request->request->get('location');
        $lat = $request->request->get('lat');
        $lng = $request->request->get('lng');
        $description = $request->get('description_rendez_vous');
        $startdateRDV = new \DateTime($this->dateToMySQL($startdate));
        $enddateRDV = new \DateTime($this->dateToMySQL($enddate));

        $rendezVous->setLocation($location);
        $rendezVous->setEndheur($endHeur);
        $rendezVous->setStartheur($startHeur);
        $rendezVous->setLat($lat);
        $rendezVous->setLng($lng);
        $rendezVous->setStartDate($startdateRDV);
        $rendezVous->setEndDate($enddateRDV);
        $rendezVous->setTitle($title);
        $rendezVous->setDescription($description);
        $rendezVous->setVip($member);
        if($media != 0){
            $entity = $em->getRepository('BackBundle:Medias')->find($media);
            $rendezVous->setMedia($entity);
        }
        $em->persist($rendezVous);
        $em->flush();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('collectionrendezVoussuccess', $translator->trans('alert.add'));
        return $this->redirect($this->generateUrl('collection', array("activeTab" => $activeTab, 'fullName' => $member->getUsername(), 'id' => $member->getId())));


    }

    /**
     * Page Add  Picture
     * @return Response A Response instance
     **/
    public function collectionDeleteRdvAction($id)
    {

        $em = $this->getDoctrine()->getManager();


        $Rendezvous = $em->getRepository('BackBundle:Rendezvous')->find($id);
        if ($Rendezvous != null) {
            $em->remove($Rendezvous);
            $em->flush();
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('collectionrendezVoussuccess', $translator->trans('alert.delete'));

        return $this->redirect($this->generateUrl('collection', array("activeTab" => "rendez-vous", 'fullName' => $this->getUser()->getUsername(), 'id' => $this->getUser()->getId())));

    }


    /**
     * get angenda collection
     */
    public function getAgendaAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $Liste_Rendezvous = $em->getRepository('BackBundle:Rendezvous')->findBy(array("vip" => $id), array("startDate" => "DESC"));
        $events = array();
        if ($Liste_Rendezvous != null) {
            foreach ($Liste_Rendezvous as $value) {
                $event = new  \stdClass();
                $event->name = $value->getTitle();
                $event->description = utf8_encode($value->getDescription());

                $event->day = $value->getStartDate()->format('d');
                $event->month = $value->getStartDate()->format('m');
                $event->year = $value->getStartDate()->format('Y');

                $event->duration = $value->getEndDate()->format('Ymd') - $value->getStartDate()->format('Ymd');

                $date = date("Ymd");
                if ($date == $value->getStartDate()->format('Ymd')) {
                    $event->color = "3";
                } elseif ($date < $value->getStartDate()->format('Ymd')) {
                    $event->color = "1";
                } else {
                    $event->color = "4";
                }
                array_push($events, $event);
            }
        }
        echo json_encode($events);
        exit();
    }

    /**
     * Page Add  Picture
     * @param Request $request The current request.
     * @param string $activeTab The Tab Active identifier.
     *
     * @return Response A Response instance
     **/

    /******kamilt il news et rdv ki tkamil il ba9i fasa5a il function hathi*****/

    public function collectionAddItemAction(Request $request, $activeTab)
    {

        $em = $this->getDoctrine()->getManager();
        $member = $this->getUser();
        $translator = $this->get('translator');

        $media = new Collectionmedia();
        /** upload image **/
        $picture = $_FILES['picture']['name'];
        $tmp_name_array = $_FILES['picture']['tmp_name'];
        $size_array = $_FILES['picture']['size'];
        if ($size_array > 0) {
            $extension = pathinfo($picture, PATHINFO_EXTENSION);
            $picture = md5(uniqid()) . "." . $extension;
            move_uploaded_file($tmp_name_array, 'upload/' . $picture);
            $media->setPicture($picture);
        }
        /** url de de vidéo  **/
        $typeVideo = $request->get('typeVideo');
        $url = $request->get('url');

        /** mode video **/
        $media->setModeMedia($request->get('modeVideo'));
        switch ($typeVideo) {
            case 1:
                $media->setTypeMedia('Url');
                if ($url != null) {
                    $urlVideo = $this->getIdvideo($url);
                    $media->setSourcevideo($urlVideo[0]);
                    $media->setUrl($urlVideo[1]);
                }
                break;
            case 2:
                $media->setTypeMedia('mp4');
                $picture = $_FILES['mediaVidoe']['name'];
                $tmp_name_array = $_FILES['mediaVidoe']['tmp_name'];
                $size_array = $_FILES['mediaVidoe']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPath($picture);
                }

                break;
            case 3:
                $media->setTypeMedia('mp3');
                $picture = $_FILES['imageMediaMP3']['name'];
                $tmp_name_array = $_FILES['imageMediaMP3']['tmp_name'];
                $size_array = $_FILES['imageMediaMP3']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPicture($picture);
                }

                $picture = $_FILES['mediaMP3']['name'];
                $tmp_name_array = $_FILES['mediaMP3']['tmp_name'];
                $size_array = $_FILES['mediaMP3']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPath($picture);
                }

                break;
            case 4:
                $media->setTypeMedia('book');
                $picture = $_FILES['mediapathBook']['name'];
                $tmp_name_array = $_FILES['mediapathBook']['tmp_name'];
                $size_array = $_FILES['mediapathBook']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPathBook($picture);
                }
                $picture = $_FILES['mediaBook']['name'];
                $tmp_name_array = $_FILES['mediaBook']['tmp_name'];
                $size_array = $_FILES['mediaBook']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPath($picture);
                }
                $media->setDescription($request->get('descriptionBook'));
                $media->setContenuBook($request->get('contenuBook'));
                $media->setEditionBook($request->get('editionBook'));
                $media->setPriceBook($request->get('priceBook'));
                $media->setDateSortie($request->get('dateSortieBook'));
                break;
            case 5:
                $media->setTypeMedia('mp3_enfant');
                $picture = $_FILES['imageMediaMP3']['name'];
                $tmp_name_array = $_FILES['imageMediaMP3']['tmp_name'];
                $size_array = $_FILES['imageMediaMP3']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPicture($picture);
                }

                $picture = $_FILES['mediaMP3']['name'];
                $tmp_name_array = $_FILES['mediaMP3']['tmp_name'];
                $size_array = $_FILES['mediaMP3']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPath($picture);
                }

                break;

           case 6:
                 $media->setTypeMedia('contes_histoires_enfants');
               $picture = $_FILES['imageMediaMP3']['name'];
               $tmp_name_array = $_FILES['imageMediaMP3']['tmp_name'];
               $size_array = $_FILES['imageMediaMP3']['size'];
               if ($size_array > 0) {
                   $extension = pathinfo($picture, PATHINFO_EXTENSION);
                   $picture = md5(uniqid()) . "." . $extension;
                   move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                   $media->setPicture($picture);
               }
               $media->setStatusMedia($request->get('statusMedia'));
               $media->setDescriptionCourt($request->get('descriptionBook'));
               $media->setDescription($request->get('contenuBook'));
                break;

                case 7:
                $media->setTypeMedia('contes_histoires');
               $picture = $_FILES['imageMediaMP3']['name'];
               $tmp_name_array = $_FILES['imageMediaMP3']['tmp_name'];
               $size_array = $_FILES['imageMediaMP3']['size'];




               if ($size_array > 0) {
                   $extension = pathinfo($picture, PATHINFO_EXTENSION);
                   $picture = md5(uniqid()) . "." . $extension;
                   move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                   $media->setPicture($picture);
               }
               $media->setDescriptionCourt($request->get('descriptionBook'));
               $media->setDescription($request->get('contenuBook'));
                break;

                case 8:
                $media->setTypeMedia('liver_bd');


                    $picture = $_FILES['mediaBook']['name'];
                    $tmp_name_array = $_FILES['mediaBook']['tmp_name'];
                    $size_array = $_FILES['mediaBook']['size'];
                    if ($size_array > 0) {
                        $extension = pathinfo($picture, PATHINFO_EXTENSION);
                        $picture = md5(uniqid()) . "." . $extension;
                        move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                        $media->setPath($picture);
                    }



               $media->setDescriptionCourt($request->get('descriptionBook'));


            $media->setEditionBook($request->get('editionBook'));
            $media->setPriceBook($request->get('priceBook'));
            $media->setDateSortie($request->get('dateSortieBook'));
                break;

        }

        /** title   **/
        $title = $request->request->get('title');
        if ($title != null) {
            $media->setTitle($title);

        }
        $media->setMember($member);
        $media->setType($activeTab);
        $em->persist($media);
        $em->flush();

        if($typeVideo == 8){

            $picture = $_FILES['mediaBookLiverBd']['name'];
            $tmp_name_array = $_FILES['mediaBookLiverBd']['tmp_name'];
            $size_array = $_FILES['mediaBookLiverBd']['size'];

            $counteur = 1 ;
            for ($i = 0; $i < count($tmp_name_array); $i++) {
                if ($size_array[$i] > 0) {
                    $imageBook = new BookImagesBD();
                    $extension = pathinfo($picture[$i], PATHINFO_EXTENSION);
                    $pictureName = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array[$i], 'upload/' . $pictureName);
                    $imageBook->setPath($pictureName);
                    $imageBook->setNbrShow($counteur);
                    $imageBook->setBook($media);
                    if ($counteur == 1 )
                    $imageBook->setShowPageHome(1);
                    else
                    $imageBook->setShowPageHome(0);
                    $em->persist($imageBook);
                    $em->flush();
                    $counteur = $counteur + 1 ;
                }
            }

        }
        $membreVuCollection = $em->getRepository('BackBundle:MembreVuCollection')->findBy(array( "memberEmitter" => $member) );
        if ($activeTab == "photos"){
            $this->get('session')->getFlashBag()->add('collectionPicturesuccess', $translator->trans('alert.add'));
            if($membreVuCollection != null ){
                foreach ($membreVuCollection as $value){
                    $value->setCollectionPhoto(false);
                    $em->merge($value);
                    $em->flush();
                }
            }

        }

        else{
            $this->get('session')->getFlashBag()->add('collectionVideossuccess', $translator->trans('alert.add'));
            if($membreVuCollection != null ){
                foreach ($membreVuCollection as $value){
                    $value->setCollectionMedia(false);
                    $em->merge($value);
                    $em->flush();
                }
            }
        }


        return $this->redirectToRoute('collection', array('activeTab' => $activeTab, 'fullName' => $member->getUsername(), 'id' => $member->getId()));


    }

    /**
     * Page Add  magazine gallery
     * @param Request $request The current request.
     * @param string $activeTab The Tab Active identifier.
     *
     * @return Response A Response instance
     **/
    public function AddmagazinegalleryAction(Request $request, $activeTab)
    {

        $em = $this->getDoctrine()->getManager();
        $userCourant = $this->getUser();
        $media = new Magazinegallery();
        $member = $this->getUser();
        $translator = $this->get('translator');
        /** upload image **/
        $picture = $_FILES['picture']['name'];
        $tmp_name_array = $_FILES['picture']['tmp_name'];
        $size_array = $_FILES['picture']['size'];
        if ($size_array > 0) {
            $itemMagazinegalleries = $em->getRepository('BackBundle:Magazinegallery')->findBy(array("state" => 1, "typegallerys" => "vip", "usercreate" => $userCourant->getId()));
            foreach ($itemMagazinegalleries as $value) {
                $entity = $em->getRepository('BackBundle:Magazinegallery')->find($value->getId());
                $entity->setState(0);
                $em->merge($entity);
                $em->flush();
            }
            $extension = pathinfo($picture, PATHINFO_EXTENSION);
            $picture = md5(uniqid()) . "." . $extension;
            move_uploaded_file($tmp_name_array, 'upload/' . $picture);
            $media->setPicture($picture);
        }
        $media->setTypegallerys("vip");
        $media->setUsercreate($userCourant);
        $em->persist($media);
        $em->flush();

        $this->get('session')->getFlashBag()->add('magazinegallerysuccess', $translator->trans('alert.add'));

        return $this->redirectToRoute('collection', array('activeTab' => $activeTab, 'fullName' => $member->getUsername(), 'id' => $member->getId()));


    }


    /**
     * Page Add  Picture
     * @param Request $request The current request.
     * @return Response A Response instance
     **/
    public function collectionDeleteItemAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id-item');
        $typeItem = $request->request->get('type-item');
        $translator = $this->get('translator');
        $member = $this->getUser()->getId();
        $username = $this->getUser()->getUsername();
        if ("deletePictureHumeur" == $typeItem) {
            $item = $em->getRepository('BackBundle:Humeur')->find($id);
            $this->get('app.img_upload')->deleteImage($item->getPicture());
            $em->remove($item);
            $em->flush();
            $activeTab = "mon_humeur";
            $this->get('session')->getFlashBag()->add('collectionPictureHumeurdelete', $translator->trans('alert.delete'));
        } else {
            $item = $em->getRepository('BackBundle:Collectionmedia')->find($id);
            $activeTab = $item->getType();
            $member = $item->getMember()->getId();


            $username = $item->getMember()->getUsername();

            $this->get('app.img_upload')->deleteImage($item->getPicture());
            $em->remove($item);
            $em->flush();
            if ($activeTab == "photos")
                $this->get('session')->getFlashBag()->add('collectionPicturedelete', $translator->trans('alert.delete'));
            elseif ($activeTab == "videos")
                $this->get('session')->getFlashBag()->add('collectionVideosdelete', $translator->trans('alert.delete'));
            else
                $this->get('session')->getFlashBag()->add('collectionNewsdelete', $translator->trans('alert.delete'));
        }
        return $this->redirectToRoute('collection', array('activeTab' => $activeTab, 'fullName' => $username, 'id' => $member));


    }

    /**
     * Page changer  Mon Humeur
     * @param Request $request The current request.
     * @param string $activeTab The current tab.
     * @return Response A Response instance
     **/
    public function collectionMonHumeurAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $monHumeur = $request->request->get('mon_humeur');
        $member = $this->getUser();
        $member->setColoremotioncard($monHumeur);
        $em->merge($member);
        $em->flush();
        $translator = $this->get('translator');

        return new JsonResponse(array(
            'alertedit' => $translator->trans('alert.edit')
        ));

    }


    /**
     * Page Picture  Mon Humeur
     * @param Request $request The current request.
     * @param string $activeTab The current tab.
     * @return Response A Response instance
     **/
    public function collectionAddAjaxPictureHumeurAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $humeur = new Humeur();
        $type = $request->get('typePictureHumeur');
        /** upload image **/
        if (isset($_FILES['picturePictureHumeur'])) {
            $picture = $_FILES['picturePictureHumeur']['name'];
            $tmp_name_array = $_FILES['picturePictureHumeur']['tmp_name'];
            $size_array = $_FILES['picturePictureHumeur']['size'];
            if ($size_array > 0) {
                $extension = pathinfo($picture, PATHINFO_EXTENSION);
                $picture = md5(uniqid()) . "." . $extension;
                move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                $humeur->setPicture($picture);
                $humeur->setMember($this->getUser());
                $humeur->setType($type);
                $em->persist($humeur);
                $em->flush();
            }
        }

        $translator = $this->get('translator');
        $ListPictureHumeur = $em->getRepository('BackBundle:Humeur')->findBy(array("member" => $this->getUser()->getId()));

        $listImageHumeurHtml = $this->renderView('MembreBundle:Collection:humeur_liste_image.html.twig', array('ListPictureHumeur' => $ListPictureHumeur));

        return new JsonResponse(array(
            'alertadd' => $translator->trans('alert.add'),
            'listImageHumeur' => $listImageHumeurHtml
        ));

    }

    /**
     * Page changer  Mon Humeur
     * @param Request $request The current request.
     * @param string $activeTab The current tab.
     * @return Response A Response instance
     **/
    public function collectionsendmessageHumeurAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $merssage = $request->request->get('merssage');
        $energique = $request->request->get('energique');
        $fatigue = $request->request->get('fatigue');
        $colere = $request->request->get('colere');
        $deprime = $request->request->get('deprime');
        $gaite = $request->request->get('gaite');
        $zen = $request->request->get('zen');

        $member = $this->getUser();
        $id = $member->getId();
        $Listuser = null;
        for ($i = 0; $i < 6; $i++) {
            if ($zen == "on" and $i == 0)
                $Listuser = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "zen");

            if ($gaite == "on" and $i == 1)
                $Listuser = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "gaite");

            if ($deprime == "on" and $i == 2)
                $Listuser = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "deprime");

            if ($colere == "on" and $i == 3)
                $Listuser = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "colere");

            if ($fatigue == "on" and $i == 4)
                $Listuser = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "fatigue");

            if ($energique == "on" and $i == 5)
                $Listuser = $em->getRepository('BackBundle:Box')->getfansHumeurBox($id, "energique");

            if ($Listuser != null ) {
                foreach ($Listuser as $value) {
                    $messaging = new Messaging();

                    $messaging->setUseremitter($member);
                    $messaging->setUserreceiver($value->getMembre());
                    $messaging->setMessage($merssage);
                    $em->persist($messaging);
                    $em->flush();

                    $messageCourant = $this->renderView('FrontBundle:Email:email.html.twig', array(
                            'message' => $merssage,
                            'member' => $member,

                        )
                    );
                    $message = \Swift_Message::newInstance()
                        ->setSubject( "VIP Crossing vous notifie d'un message de " . $member->getNom()." ". $member->getPrenom())
                        ->setFrom(['no_reply@vipcrossing.com' => 'VIP Crossing'])
                        ->setTo($value->getMembre()->getEmail())
                        ->setBody($messageCourant , 'text/html');
                    $this->get('mailer')->send($message);

                }
                $Listuser = null;
            }

        }
        $translator = $this->get('translator');

        return new JsonResponse(array(
            'alertsendmessage' => $translator->trans('alert.envoie_message')
        ));

    }

    /**
     * Add amis
     * @param string $id the id user collection.
     * @return Response A Response instance
     **/
    public function addAmisAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);
        $userConrant = $this->getUser();
        $amis = new Listamis();
        $amis->setUserEmitter($userConrant);
        $amis->setUserReceiver($user);
        $em->persist($amis);
        $em->flush();

         $membreVuCollection = $em->getRepository('BackBundle:MembreVuCollection')->findListeCollection($user , $userConrant );

        if($membreVuCollection == null ){

            $MembreVuCollection  = new MembreVuCollection();
            $MembreVuCollection->setMemberEmitter($userConrant);
            $MembreVuCollection->setMemberCollection($user) ;

            $em->persist($MembreVuCollection);
            $em->flush();

            $MembreVuCollection  = new MembreVuCollection();
            $MembreVuCollection->setMemberEmitter($user);
            $MembreVuCollection->setMemberCollection( $userConrant) ;

            $em->persist($MembreVuCollection);
            $em->flush();

        }


        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('memebresuccess', $translator->trans('alert.add'));

        return $this->redirectToRoute('collection', array('activeTab' => "list_amis", 'fullName' => $userConrant->getUsername(), 'id' => $userConrant->getId()));


    }

    /**
     * delete amis
     * @param string $id the id user collection.
     * @return Response A Response instance
     **/
    public function deleteAmisAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackBundle:Listamis')->find($id);
        $em->remove($entity);
        $em->flush();
        $userCourant = $this->getUser();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('memebresuccess', $translator->trans('alert.delete'));

        return $this->redirectToRoute('collection', array('activeTab' => "list_amis", 'fullName' => $userCourant->getUsername(), 'id' => $userCourant->getId()));


    }

    /**
     * Page Add  Picture
     * @param Request $request The current request.
     * @return Response A Response instance
     **/
    public function collectionDeleteCommentaireAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id-item-commentaire');
        $item = $em->getRepository('BackBundle:CommentaireMedia')->find($id);
        $activeTab = "avis";
        $member = $item->getUsers()->getId();
        $em->remove($item);
        $em->flush();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('collectionAvisdelete', $translator->trans('alert.delete'));

        return $this->redirectToRoute('collection', array('activeTab' => $activeTab, 'fullName' => $item->getUsers()->getUsername(), 'id' => $member));


    }

    /**
     * Page Delete   Picture Magazine
     * @param Request $request The current request.
     * @return Response A Response instance
     **/
    public function collectionDeleteMagazineAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id-item-magazine');

        $item = $em->getRepository('BackBundle:Magazinegallery')->find($id);
        $activeTab = "magazinegallery";
        $em->remove($item);
        $em->flush();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('magazinegallerysuccess', $translator->trans('alert.delete'));
        return $this->redirectToRoute('collection', array('activeTab' => $activeTab, 'fullName' => $this->getUser()->getUsername(), 'id' => $this->getUser()->getId()));

    }

    /**
     * Page change state   Picture Magazine
     * @return Response A Response instance
     **/
    public function collectionStatemagazinegalleryAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('BackBundle:Magazinegallery')->find($id);
        $userCourant = $this->getUser();
        if ($gallery->getState() == 1) {
            $gallery->setState(0);
        } else {
            $itemMagazinegalleries = $em->getRepository('BackBundle:Magazinegallery')->findBy(array("state" => 1, "typegallerys" => "vip", "usercreate" => $userCourant->getId()));

            foreach ($itemMagazinegalleries as $value) {
                $entity = $em->getRepository('BackBundle:Magazinegallery')->find($value->getId());
                $entity->setState(0);
                $em->merge($entity);
                $em->flush();
            }
            $gallery->setState(1);
        }
        $em->merge($gallery);
        $em->flush();
        $activeTab = "magazinegallery";


        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('magazinegallerysuccess', $translator->trans('alert.state'));
        //  echo 'test';exit();
        return $this->redirectToRoute('collection', array('activeTab' => $activeTab, 'fullName' => $userCourant->getUsername(), 'id' => $userCourant->getId()));


    }

    /**
     * Page Add  Description
     * @param Request $request The current request.
     * @return Response A Response instance
     **/
    public function collectionAddDescriptionAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $userCourant = $this->getUser();
        $description = $request->request->get('description');
        $userCourant->setDescription($description);
        $em->merge($userCourant);
        $em->flush();
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('collectionDescriptionVip', $translator->trans('alert.edit'));

        return $this->redirectToRoute('collection', array('activeTab' => 'vip', 'fullName' => $userCourant->getUsername(), 'id' => $userCourant->getId()));


    }

    /************** End Liste des fonctions de partie collection ******************/

    /************** End Liste des fonctions de partie Box ******************/

    /**
     * Page index Box active
     * @param  Request $request
     * @return Response
     */

    public function boxactiveAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $userCourant = $this->getUser();
        $Personnage_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "crossing-cartoon", 'enable' => 1));
        $Cinema_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "cinema", 'enable' => 1));
        $searchWord_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "wordsearch", 'enable' => 1));


        $Pro_in_box = $em->getRepository('BackBundle:Box')->getUserBoxByRole($userCourant->getId(), 1, "ROLE_PRO", 0);
        $Vip__in_box = $em->getRepository('BackBundle:Box')->getUserBoxByRole($userCourant->getId(), 1, "ROLE_VIP", 0);
        $Vip_fan_in_box = $em->getRepository('BackBundle:Box')->getUserBoxByRole($userCourant->getId(), 1, "ROLE_VIP", 1);

        $Spectacle_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "spectacle", 'enable' => 1));
        $People_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "people", 'enable' => 1));
        $Radio_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "radio", 'enable' => 1));
        $Concert_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "concert", 'enable' => 1));
        $Programme_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "programme", 'enable' => 1));
        $gallery_banner = $em->getRepository('BackBundle:Gallerys')->getRandomSingleEntity("gallery_banner");
        $session = $request->getSession();
        $session->set('activerouteCourant', "box");
        $gallerysBanners = $em->getRepository ( 'BackBundle:Gallerys' )->getRandomEntities ( 15 , 'gallery_banner' );
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);

        return $this->render('MembreBundle:Box:index.html.twig', array(

            "member" =>  $this->getUser(),
            "listeVIP" => $liste_VIP,
            'proInBox' => $Pro_in_box,
            'vipInBox' => $Vip__in_box,
            'vipFanInBox' => $Vip_fan_in_box,
            'gallerysBanners' => $gallerysBanners ,

            'searchWordInBox' => $searchWord_in_box,
            'cinemaInBox' => $Cinema_in_box,
            //  'vipInBox' => $Vip_in_box ,
            'programmeInBox' => $Programme_in_box,
            'radioInBox' => $Radio_in_box,
            'peopleInBox' => $People_in_box,
            'spectacleInBox' => $Spectacle_in_box,
            'concertInBox' => $Concert_in_box,
            'gallerybanner' => $gallery_banner,
            'personnageInBox' => $Personnage_in_box,
            'title' => "Box active",
        ));
    }

    /**
     * Page archiver or delete box
     * @return Response
     */

    public function boxArchiveOrDeleteAction($id)
    {
        $this->get('session')->set('activerouteCourant', 'box');

        $em = $this->getDoctrine()->getManager();
        $box = $em->getRepository('BackBundle:Box')->find($id);
        $translator = $this->get('translator');
        if ($box->getEnable() == 1) {
            $this->get('session')->getFlashBag()->add('boxdelete', $translator->trans('alert.delete_box'));
            $box->setEnable(0);
            $em->merge($box);
            $em->flush();
            return $this->redirectToRoute('front_end_page_box');

        } else {
            $this->get('session')->getFlashBag()->add('boxdelete', $translator->trans('alert.delete'));
            $em->remove($box);
            $em->flush();
            return $this->redirectToRoute('front_end_page_box_non_active');

        }

    }

    /**
     * Page partage box
     * @param  Request $request
     * @return Response
     */

    public function partageBoxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listeMembre = $em->getRepository('UserBundle:User')->findByRole("ROLE_MEMBRE");

        $gallery_banner = $em->getRepository('BackBundle:Gallerys')->getRandomSingleEntity("gallery_banner");
        if ($request->getMethod() == "POST") {
            $ListIduser = $request->get('ListIduser');
            $userCourant = $this->getUser();
            if ($ListIduser != null) {
                foreach ($ListIduser as $value) {
                    $user = $em->getRepository('UserBundle:User')->find($value);
                    $messaging = new Messaging();
                    $messageCourant = $this->renderView('MembreBundle:PartageBox:messageCourant.html.twig', array(
                            'user' => $user,
                            'userCourant' => $userCourant
                        )
                    );
                    $messaging->setUseremitter($userCourant);
                    $messaging->setUserreceiver($user);
                    $messaging->setMessage($messageCourant);
                    $em->persist($messaging);
                    $em->flush();

                    $message = \Swift_Message::newInstance()
                        ->setSubject( "VIP Crossing vous notifie d'un message de " . $userCourant->getNom()." ". $userCourant->getPrenom())
                        ->setFrom(['no_reply@vipcrossing.com' => 'VIP Crossing'])
                        ->setTo($user->getEmail())
                        ->setBody($messageCourant, 'text/html');
                    $this->get('mailer')->send($message);
echo 'test'; exit();
                }
            }
            $translator = $this->get('translator');
            $this->get('session')->getFlashBag()->add('partageBoxenvoie', $translator->trans('alert.envoie_message'));

            return $this->redirectToRoute('collection_partage_box');

        }

        return $this->render('MembreBundle:PartageBox:index.html.twig', array(
            'gallerybanner' => $gallery_banner,
            'listeMembre' => $listeMembre,
            'personnageInBox' => null,
        ));

    }


    /**
     * Page partage box
     * @param  Request $request
     * @return Response
     */

    public function partageBookAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery_banner = $em->getRepository('BackBundle:Gallerys')->getRandomSingleEntity("gallery_banner");

        $listeBook = $em->getRepository('BackBundle:Payments')->findBy(array('user' => $this->getUser()));
        return $this->render('MembreBundle:PartageBox:listBook.html.twig', array(
            'gallerybanner' => $gallery_banner,
            'listeBook' => $listeBook,
        ));

    }

    /**
     * Page partage box envoie email
     * @param  Request $request
     * @return Response
     */

    public function partageBoxEmailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userCourant = $this->getUser();
        $name = $request->get('name');
        $email = $request->get('email');
        $messageCourant = $this->renderView('MembreBundle:PartageBox:messageEmail.html.twig', array(
                'name' => $name,
                'userCourant' => $userCourant
            )
        );

        $message = \Swift_Message::newInstance()
            ->setSubject( "VIP Crossing vous notifie d'un message de " . $userCourant->getNom()." ". $userCourant->getPrenom())
             ->setFrom(['no_reply@vipcrossing.com' => 'VIP Crossing'])

            ->setTo($email)
            ->setBody($messageCourant, 'text/html');
        $this->get('mailer')->send($message);

        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->add('partageBoxenvoie', $translator->trans('alert.envoie_message'));

        return $this->redirectToRoute('collection_partage_box');


    }

    /**
     * Page index Box non active
     * @param  Request $request
     * @return Response
     */

    public function boxnonactiveAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $userCourant = $this->getUser();
        $Personnage_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "crossing-cartoon", 'enable' => 0));

        $Cinema_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "cinema", 'enable' => 0));

        $Pro_in_box = $em->getRepository('BackBundle:Box')->getUserBoxByRole($userCourant->getId(), 1, "ROLE_PRO", 0);
        $Vip__in_box = $em->getRepository('BackBundle:Box')->getUserBoxByRole($userCourant->getId(), 1, "ROLE_VIP", 0);
        $Vip_fan_in_box = $em->getRepository('BackBundle:Box')->getUserBoxByRole($userCourant->getId(), 1, "ROLE_VIP", 1);

        $Spectacle_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "spectacle", 'enable' => 0));
        $People_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "people", 'enable' => 0));
        $Radio_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "radio", 'enable' => 0));
        $Concert_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "concert", 'enable' => 0));
        $Programme_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "programme", 'enable' => 0));
        $gallery_banner = $em->getRepository('BackBundle:Gallerys')->getRandomSingleEntity("gallery_banner");
        $searchWord_in_box = $em->getRepository('BackBundle:Box')->findBy(array("membre" => $userCourant->getId(), "typebox" => "wordsearch", 'enable' => 0));
        $session = $request->getSession();
        $session->set('activerouteCourant', "box");

        return $this->render('MembreBundle:Box:index.html.twig', array(

            'searchWordInBox' => $searchWord_in_box,
            'cinemaInBox' => $Cinema_in_box,
            'proInBox' => $Pro_in_box,
            'vipInBox' => $Vip__in_box,
            'vipFanInBox' => $Vip_fan_in_box,
            'programmeInBox' => $Programme_in_box,
            'radioInBox' => $Radio_in_box,
            'peopleInBox' => $People_in_box,
            'spectacleInBox' => $Spectacle_in_box,
            'concertInBox' => $Concert_in_box,
            'gallerybanner' => $gallery_banner,
            'title' => "Box non active",
            'personnageInBox' => $Personnage_in_box,
        ));
    }

    /**
     * Page add vip box
     * @param  Request $request
     * @return Response
     */

    public function addvipboxAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $userCourant = $this->getUser();
        $listVip = $request->get('listeVip');
        if ($listVip != null) {
            foreach ($listVip as $value) {
                $vipUsers = $em->getRepository('UserBundle:User')->find($value);

                $box = new Box();
                $box->setMembre($userCourant);
                $box->setTypebox("user");
                $box->setVip($vipUsers);

                $em->persist($box);
                $em->flush();

            }
        }
        $session = $request->getSession();

        return $this->redirectToRoute('front_end_index', array("page" => $session->get('page')));

    }

    /**
     * Page ajax List media vip.
     *
     * @param Request $request The current request.
     *
     * @return JsonResponse A JsonResponse instance
     **/
    public function addOrRemoveboxajaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('id');
        $typeAction = $request->get('typeAction');
        $typebox = $request->get('typebox');
        $userCourant = $this->getUser();
        $entity = null;

        if ($typebox == 'user')
            $item = $em->getRepository('UserBundle:User')->find($id);
        else
            $item = $em->getRepository('BackBundle:Medias')->find($id);

        if ($typeAction == "add") {
            $entity = new Box();
            $entity->setMembre($userCourant);

            if ($typebox == 'user')
                $entity->setVip($item);
            else
                $entity->setMedias($item);

            $entity->setTypebox($typebox);
            $em->persist($entity);

        } else {
            if ($typebox == 'user')
                $User_in_box = $em->getRepository('BackBundle:Box')->findOneBy(array("vip" => $item->getId(), "membre" => $userCourant->getId(), "typebox" => $typebox));
            else
                $User_in_box = $em->getRepository('BackBundle:Box')->findOneBy(array("medias" => $item->getId(), "membre" => $userCourant->getId(), "typebox" => $typebox));

            $em->remove($User_in_box);
        }
        $em->flush();

        $boxHtml = $this->renderView('MembreBundle:Box:ajaxbox.html.twig', array('item' => $item, 'itemInBox' => $entity, "typebox" => $typebox));

        return new JsonResponse(array(
            'boxHtml' => $boxHtml
        ));
    }

    /************** End Liste des fonctions de partie Box ******************/


    /**
     * Page d'un membre
     * @param Request $request The current request.
     * @return Response A Response instance
     **/
    public function RegistrationAction(Request $request)
    {

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
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                $roles = array($form->get('typecompte')->getData());

                $user->setRoles($roles);

                if($roles[0] == "ROLE_FAN"){
                    $user->setRoles( array('ROLE_VIP') );
                    $user->setFan(1);
                }

                $userManager->updateUser($user);
                if (null === $response = $event->getResponse()) {

                    $url = $this->generateUrl('front_confirmed', array('id' => $user->getId()));

                    $response = new RedirectResponse($url);
                }
                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }


        return $this->render('MembreBundle:Registration:registration.html.twig', array(

            'form' => $form->createView(),
            'errors' => $form->getErrors(),
        ));
    }

    /**
     * Tell the user his account is now confirmed.
     * @param string $id The User identifier.
     * @return Response A Response instance
     */
    public function confirmedAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('UserBundle:User')->find($id);

        return $this->render('MembreBundle:Registration:confirmed.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * show profile
     * @param Request $request The current request.
     * @return Response A Response instance
     */
    public function profileAction(Request $request)
    {

        $this->get('session')->set('activerouteCourant', '');
        $errors = null;
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.change_password.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                /** @var $userManager UserManagerInterface */
                $userManager = $this->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('espace-member-profile');
                    $translator = $this->get('translator');
                    $message = $translator->trans('change_password.flash.success');
                    $this->get('session')->getFlashBag()->add('front_profile_updated', $message);

                    $response = new RedirectResponse($url);

                }

                $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            } else {

                $errors = $form->getErrors();

            }
        }
        $em = $this->getDoctrine()->getManager();
        $numbreMessageNotSeen = $em->getRepository('BackBundle:Messaging')->CountMessageNotSeen($user->getId(), 0);

        return $this->render('MembreBundle:Membre:profile.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'errors' => $errors,
            'numbreMessageNotSeen' => $numbreMessageNotSeen
        ));
    }

    /**
     * Edit the user.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editprofileAction(Request $request)
    {

        $user = $this->getUser();
        $old_pictureprofil = $user->getPictureprofil();
        $old_picturecover = $user->getPicturecover();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

            $user->setPictureprofil($old_pictureprofil);

            $user->setPicturecover($old_picturecover);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);
            $userManager->updateUser($user);
            $this->get('session')->set('pictureprofil', null);
            $this->get('session')->set('picturecover', null);
            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('espace-member-profile');
                $translator = $this->get('translator');
                $message = $translator->trans('profile.flash.updated');
                $this->get('session')->getFlashBag()->add('front_profile_updated', $message);

                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('MembreBundle:Membre:edit_profile.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    /****** function Ajax ******/
    /**
     * Page Add  News
     * @param Request $request The current request.
     *
     * @return Response A Response instance
     **/
    public function collectionAddAjaxItemAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $member = $this->getUser();
        $translator = $this->get('translator');
        $activeTab = $request->get('activeTab');
        $collectionsuccess = $translator->trans('alert.add');
        if ($activeTab == "news") {
            $title = $request->get('title');
            $description = $request->get('description');
            $descriptionCourt = $request->get('descriptionCourt');


            $media = new Collectionmedia();
            /** upload image **/
            if (isset($_FILES['picture'])) {
                $picture = $_FILES['picture']['name'];
                $tmp_name_array = $_FILES['picture']['tmp_name'];
                $size_array = $_FILES['picture']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPicture($picture);
                }
            }
            $media->setTitle($title);
            $media->setDescription($description);
            $media->setDescriptionCourt ( $descriptionCourt );
            $media->setMember($member);
            $media->setType($activeTab);
            $em->persist($media);
            $em->flush();

             $membreVuCollection = $em->getRepository('BackBundle:MembreVuCollection')->findBy(array( "memberEmitter" => $member) );


            if($membreVuCollection != null ){
                foreach ($membreVuCollection as $value){

                $value->setCollectionNews(false);
                $em->merge($value);
                $em->flush();

                 }
            }


            $Liste_News = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMedia("news", $member->getId());
            $ListeNewsHtml = $this->renderView('MembreBundle:Ajax:Listenews.html.twig', array(
                    'member' => $member,
                    'listeNews' => $Liste_News
                )
            );
            $ItemVipNews = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediafix(4, "news", $member->getId());
            $listeNewsVipHtml = $this->renderView('MembreBundle:CollectionNews:liste_news_vip.html.twig', array('ItemNews' => $ItemVipNews));
            return new JsonResponse(array(
                'collectionsuccess' => $collectionsuccess,
                'activeTab' => $activeTab,
                'ListeNewsHtml' => $ListeNewsHtml,
                'listeNewsVipHtml' => $listeNewsVipHtml,
            ));
        }
        elseif ($activeTab == "rendez-vous") {

            $rendezVous = new Rendezvous();
            $title = $request->request->get('title');


            $startdate = $request->request->get('startdate');
            $enddate = $request->request->get('enddate');
            $startdateRDV = new \DateTime($this->dateToMySQL($startdate));
            $enddateRDV = new \DateTime($this->dateToMySQL($enddate));


            $rendezVous->setStartDate($startdateRDV);
            $rendezVous->setEndDate($enddateRDV);
            $rendezVous->setTitle($title);
            $rendezVous->setVip($member);
            $em->persist($rendezVous);
            $em->flush();
            //  $Liste_Rendezvous = $em->getRepository ( 'BackBundle:Rendezvous' )->findBy ( array( "vip" => $member->getId () ) , array( "startDate" => "DESC" ) );
            $ListeRendezvousHtml = $this->renderView('MembreBundle:CollectionRDV:liste_rdv.html.twig', array('id' => $member->getId()));

            return new JsonResponse(array(
                'collectionsuccess' => $collectionsuccess,
                'activeTab' => $activeTab,
                'ListeRendezvousHtml' => $ListeRendezvousHtml,
            ));

        }

    }

  /**
     * Page Add  News
     * @param Request $request The current request.
     *
     * @return Response A Response instance
     **/
    public function collectionAddNewsAction(Request $request , $id)
    {

        $em = $this->getDoctrine()->getManager();
        $member = $this->getUser();

        if( $id == null ){
            $media = new Collectionmedia();

        }else{
            $media = $em->getRepository('BackBundle:Collectionmedia')->find($id);

        }


        if( $request->getMethod() == 'POST'){
        $activeTab = "news";
             $title = $request->get('title');
            $description = $request->get('description');
            $descriptionCourt = $request->get('descriptionCourt');



            /** upload image **/
            if (isset($_FILES['picture'])) {
                $picture = $_FILES['picture']['name'];
                $tmp_name_array = $_FILES['picture']['tmp_name'];
                $size_array = $_FILES['picture']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPicture($picture);
                }
            }
            $media->setTitle($title);
            $media->setDescription($description);
            $media->setDescriptionCourt ( $descriptionCourt );
            $media->setMember($member);
            $media->setType($activeTab);
            $em->persist($media);
            $em->flush();
            $translator = $this->get('translator');

            if( $id == null ){

                $this->get ( 'session' )->getFlashBag ()->add ( 'collectionNews' , $translator->trans ( 'alert.add' ) );
            }else{

                $this->get ( 'session' )->getFlashBag ()->add ( 'collectionNews' , $translator->trans ( 'alert.edit' ) );
            }


            return $this->redirectToRoute('collection', array('activeTab' => $media->getType(), 'fullName' => $this->getUser()->getUsername(), 'id' => $this->getUser()->getId()));
        }


        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);
        return $this->render('MembreBundle:CollectionNews:edit_news.html.twig', array(
            'media' => $media,
            'id' => $id,
            'listeVIP' => $liste_VIP,
            'member' =>$this->getUser(),


        ));

    }

 /**
     * Page Add  News
     * @param Request $request The current request.
     *
     * @return Response A Response instance
     **/
    public function changeEtatAction(Request $request , $id)
    {

        $em = $this->getDoctrine()->getManager();
        $member = $this->getUser();

        $Liste_Photos = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMedia("photos", $member->getId());



        foreach ($Liste_Photos as $value){
            $value->setShowPageHome(0);
            $em->merge($value);
            $em->flush();

        }
        $media = $em->getRepository('BackBundle:Collectionmedia')->find($id);
        $media->setShowPageHome(1);
        $em->merge($media);
        $em->flush();


            return $this->redirectToRoute('collection', array('activeTab' => "photos", 'fullName' => $this->getUser()->getUsername(), 'id' => $this->getUser()->getId()));




    }


  /**
     * Page Add  News
     * @param Request $request The current request.
     *
     * @return Response A Response instance
     **/
    public function showArticleEnfantAction(Request $request , $id)
    {

        $em = $this->getDoctrine()->getManager();

        $media = $em->getRepository('BackBundle:Collectionmedia')->find($id);
        $member = $this->getUser();

        $entitiesTotla = $em->getRepository('BackBundle:Box')->findBy(array("vip" => $id, "typebox" => "user"));

        if ($entitiesTotla != null)
            $totla = count($entitiesTotla);
        else
            $totla = 0;
       
            $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);
            
            $listeContesHistoiresEnfantsAmiAutre = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaAmiAussiAutre("videos",$media->getMember()->getId() , 'contes_histoires_enfants');
            $listeContesHistoiresEnfantsAmi = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediaAmiAussi("videos",$media->getMember()->getId() , 'contes_histoires_enfants');
             return $this->render('MembreBundle:Collection:contes_histoires.html.twig', array(
            'listeContesHistoiresEnfantsAmiAutre' => $listeContesHistoiresEnfantsAmiAutre ,
                'nextEvents' => $listeContesHistoiresEnfantsAmi ,
           'article' => $media,
            "listeVIP" => $liste_VIP,
            'id' => $id,
            'totla' => $totla,
            'member' =>$media->getMember(),


        ));

    }



    /**
     * Page Delete  News
     * @param Request $request The current request.
     * @return Response A Response instance
     **/
    public function collectionDeleteNewsAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id-item');

        $item = $em->getRepository('BackBundle:Collectionmedia')->find($id);
        $this->get('app.img_upload')->deleteImage($item->getPicture());
        $em->remove($item);
        $em->flush();
        $translator = $this->get('translator');
        $collectionNewsdelete = $translator->trans('alert.delete');
        $member = $item->getMember();
        $activeTab = $item->getType();

        $Liste_News = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMedia("news", $member->getId());
        $ListeNewsHtml = $this->renderView('MembreBundle:Ajax:Listenews.html.twig', array(
                'member' => $member,
                'listeNews' => $Liste_News
            )
        );
        $ItemVipNews = $em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediafix(4, "news", $member->getId());
        $listeNewsVipHtml = $this->renderView('MembreBundle:CollectionNews:liste_news_vip.html.twig', array('ItemNews' => $ItemVipNews));

        return new JsonResponse(array(
            'collectionNewsdelete' => $collectionNewsdelete,
            'activeTab' => $activeTab,
            'ListeNewsHtml' => $ListeNewsHtml,
            'listeNewsVipHtml' => $listeNewsVipHtml,
        ));
    }

    /********** End Function Ajax **************/
    /**
     * Uploadpicture Profil
     *
     * @param String $typepicture the  Type Picture
     * @return Response
     */
    public function UploadpictureAction($typepicture)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $images = $this->get('app.img_upload')->getImages();
        } catch (Exception $e) {
            $this->get('app.img_upload')->outputJSON('failure');
            return;
        }

        // if no images found
        if (count($images) === 0) {
            $this->get('app.img_upload')->outputJSON('failure');
            return;
        }

        $user = $this->getUser() ;
        // should always be one file (when posting async)
        $image = $images[0];
        $file = $this->get('app.img_upload')->saveFile($image['output']['data'], $image['output']['name']);

        // echo results
        $this->get('app.img_upload')->outputJSON('success', $file['name'], $file['path']);
        if ($typepicture == 'profil') {
            if ($user->getPictureprofil() != null)
                $this->get('app.img_upload')->deleteImage($user->getPictureprofil());

            $user->setPictureprofil($file['name']);

            $this->get('session')->set('pictureprofil', $file['name']);
        } else {
            if ($user->getPicturecover() != null)
                $this->get('app.img_upload')->deleteImage($user->getPicturecover());

            $user->setPicturecover($file['name']);

            $this->get('session')->set('picturecover', $file['name']);
        }
        $em->merge($user);
        $em->flush();
        exit();
    }


    public function getIdvideo($url)
    {
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
                $formated_url = "$id";

                break;
            case 'vimeo':
                $formated_url = "$id";

                break;
            case 'dailymotion':
                $formated_url = "$id";

                break;

            default:
                break;
        }

        $coordonnses_video[0] = $host;
        $coordonnses_video[1] = $formated_url;
        return $coordonnses_video;
    }


    private function dateToMySQL($date)
    {
        $tabDate = explode('/', $date);
        $date = $tabDate[2] . '-' . $tabDate[1] . '-' . $tabDate[0];
        $date = date('Y-m-d', strtotime($date));
        return $date;
    }


    /**
     * ajouter personnage concernant collectionmedia
     */
    public function collectionAddPersonnageAction(Request $request)
    {
        $livre = intval($request->get('livre'));
        $id_member = intval($request->get('member'));
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('UserBundle:User')->find($id_member);
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);
        $personnageMediaList = $em->getRepository('BackBundle:PersonnageMedia')->findBy(array('collectionMedia' => $livre));
        $personnageMedia = new PersonnageMedia();
        $form = $this->createForm("BackBundle\Form\PersonnageMediaType", $personnageMedia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($personnageMedia->getImage()) {
                $image = $personnageMedia->getImage();
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('upload_dir') . "/personnage_media",
                    $imageName
                );
                $personnageMedia->setImage($imageName);
            }
            $collectionMedia = $em->getRepository('BackBundle:Collectionmedia')->find($livre);
            $personnageMedia->setCollectionMedia($collectionMedia);
            $em->persist($personnageMedia);
            $em->flush();
            return $this->redirectToRoute('collection_add_personnage', array('livre' => $livre, 'member' => $this->getUser()->getId()));
        }

        return $this->render('MembreBundle:Collection:ajouter_personnage.html.twig', array(
            "livre" => $livre,
            "form" => $form->createView(),
            "personnageMediaList" => $personnageMediaList,
            "listeVIP" => $liste_VIP,
            "member" => $member
        ));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function collectionChangeEtatImageBdAction ( $idBd , $idMedia )
    {
        $em = $this->getDoctrine ()->getManager ();



        $BookImagesBD = $em->getRepository('BackBundle:BookImagesBD')->findOneBy(array("book" => $idMedia , "showPageHome" => 1 ),array("nbrShow" => "ASC"));

        if($BookImagesBD != null  ){
            $BookImagesBD->setShowPageHome(0);
            $em->merge($BookImagesBD);
            $em->flush();
        }

        $entity = $em->getRepository ( 'BackBundle:BookImagesBD' )->find ( $idBd );

        $entity->setShowPageHome(1);
        $em->merge($entity);
        $em->flush();


        $translator = $this->get ( 'translator' );
        $this->get('session')->getFlashBag()->add('collectionDeleteBd', $translator->trans('alert.delete'));

        return $this->redirectToRoute('collection_edit_book', array('livre' => $idMedia ));


    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function collectionDeleteBdAction ( Request $request )
    {
        $em = $this->getDoctrine ()->getManager ();

        $idBd = $request->get('id-bd-item');
        $idMedia = $request->get('id-media');
        $bookImagesBD = $em->getRepository ( 'BackBundle:BookImagesBD' )->find ( $idBd );


        $em->remove($bookImagesBD);
        $em->flush();


        $BookImagesBD = $em->getRepository('BackBundle:BookImagesBD')->findBy(array("book" => $idMedia),array("nbrShow" => "ASC"));

        if(count($BookImagesBD) > 0 ){
            $i = 1 ;
            foreach ($BookImagesBD as   $value)
           {
                $value->setNbrShow($i);
                $em->merge($value);
                $em->flush();
               $i ++ ;
            }
        }

        $translator = $this->get ( 'translator' );
        $this->get('session')->getFlashBag()->add('collectionDeleteBd', $translator->trans('alert.delete'));

        return $this->redirectToRoute('collection_edit_book', array('livre' => $idMedia ));


    }


    /**
     * edit book concernant collectionmedia
     */
    public function collectionEditBookAction(Request $request, $livre)
    {

        $em = $this->getDoctrine()->getManager();
        $member = $this->getUser();
        $translator = $this->get('translator');

        $media = $em->getRepository('BackBundle:Collectionmedia')->find($livre);
        $BookImagesBD = $em->getRepository('BackBundle:BookImagesBD')->findBy(array("book" => $media->getId()),array("nbrShow" => "ASC"));
    
    
        if ($request->getMethod() == "POST") {
            $typeMedia = $request->get('typeMedia') ;               
              if( $typeMedia == "contes_histoires_enfants"){ 
                $media->setStatusMedia($request->get('statusMedia'));
                
                $picture = $_FILES['picture']['name'];
                $tmp_name_array = $_FILES['picture']['tmp_name'];
                $size_array = $_FILES['picture']['size'];
                if ($size_array > 0) {
                    $extension = pathinfo($picture, PATHINFO_EXTENSION);
                    $picture = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                    $media->setPicture($picture);
                }
                $media->setDescriptionCourt($request->get('descriptionCourt'));
                $media->setDescription($request->get('descriptionHistoires'));
     

            }elseif( $typeMedia == "liver_bd"){

                  $orderShows = $request->get('orderShow');
                  if(count($BookImagesBD) > 0 ){
                      foreach ($BookImagesBD as $key => $value){
                          $value->setNbrShow($orderShows[$key]);
                          $em->merge($value);
                          $em->flush();
                      }
                  }
                  $nbrImage = $request->get('nbrImage');
            $picture = $_FILES['mediaBookLiverBd']['name'];
            $tmp_name_array = $_FILES['mediaBookLiverBd']['tmp_name'];
            $size_array = $_FILES['mediaBookLiverBd']['size'];

            $counteur = 1 ;
            for ($i = 0; $i < count($tmp_name_array); $i++) {
                if ($size_array[$i] > 0) {
                    $nbrImage = $nbrImage + 1 ;
                    $imageBook = new BookImagesBD();
                    $extension = pathinfo($picture[$i], PATHINFO_EXTENSION);
                    $pictureName = md5(uniqid()) . "." . $extension;
                    move_uploaded_file($tmp_name_array[$i], 'upload/' . $pictureName);
                    $imageBook->setPath($pictureName);
                    $imageBook->setNbrShow($nbrImage);
                    $imageBook->setBook($media);
                    $em->persist($imageBook);
                    $em->flush();
                    $counteur = $counteur + 1 ;
                }
            }



            $picture = $_FILES['mediaBook']['name'];
            $tmp_name_array = $_FILES['mediaBook']['tmp_name'];
            $size_array = $_FILES['mediaBook']['size'];
            if ($size_array > 0) {
                $extension = pathinfo($picture, PATHINFO_EXTENSION);
                $picture = md5(uniqid()) . "." . $extension;
                move_uploaded_file($tmp_name_array, 'upload/' . $picture);
                $media->setPath($picture);
            }

            $media->setEditionBook($request->get('editionBook'));
            $media->setPriceBook($request->get('priceBook'));
            $media->setDateSortie($request->get('dateSortieBook'));
            $media->setDescriptionCourt($request->get('descriptionCourt'));
           


            }
            
            
            else{
                
               
               
        $picture = $_FILES['mediapathBook']['name'];
        $tmp_name_array = $_FILES['mediapathBook']['tmp_name'];
        $size_array = $_FILES['mediapathBook']['size'];
        if ($size_array > 0) {
            $extension = pathinfo($picture, PATHINFO_EXTENSION);
            $picture = md5(uniqid()) . "." . $extension;
            move_uploaded_file($tmp_name_array, 'upload/' . $picture);
            $media->setPathBook($picture);
        }
        $picture = $_FILES['mediaBook']['name'];
        $tmp_name_array = $_FILES['mediaBook']['tmp_name'];
        $size_array = $_FILES['mediaBook']['size'];
        if ($size_array > 0) {
            $extension = pathinfo($picture, PATHINFO_EXTENSION);
            $picture = md5(uniqid()) . "." . $extension;
            move_uploaded_file($tmp_name_array, 'upload/' . $picture);
            $media->setPath($picture);
        }
        $media->setDescription($request->get('descriptionBook'));
        $media->setContenuBook($request->get('contenuBook'));
        $media->setEditionBook($request->get('editionBook'));
        $media->setPriceBook($request->get('priceBook'));
        $media->setDateSortie($request->get('dateSortieBook'));
      
            }

        /** title   **/
        $title = $request->request->get('title');
        if ($title != null) {
            $media->setTitle($title);

        }
        $em->merge($media);
        $em->flush();


        $this->get('session')->getFlashBag()->add('collectionVideossuccess', $translator->trans('alert.edit'));

        return $this->redirectToRoute('collection', array('activeTab' => $media->getType(), 'fullName' => $member->getUsername(), 'id' => $member->getId()));

    }
  
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);

        return $this->render('MembreBundle:EditBook:edit_book.html.twig', array(
            "media" => $media,
            "listeVIP" => $liste_VIP,

            "BookImagesBD" => $BookImagesBD,
            "member" => $member
        ));

    }

    /**
     * afficher la liste des personnages concernant collection media donneée
     */
    public function collectionListPersonnageAction(Request $request)
    {
        $livre = $request->get('livre');
        $id_member = intval($request->get('member'));
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('UserBundle:User')->find($id_member);
        $liste_VIP = $em->getRepository('UserBundle:User')->findByRoleRANDFixe("ROLE_VIP", 4);
        $personnagesMedia = $em->getRepository('BackBundle:PersonnageMedia')->findBy(['collectionMedia' => $livre]);
        $book = $em->getRepository('BackBundle:Collectionmedia')->find($livre);
        return $this->render('MembreBundle:Collection:list_personnage.html.twig', array(
            "book" => $book,
            "livre" => $livre,
            "personnagesMedia" => $personnagesMedia,
            "listeVIP" => $liste_VIP,
            "member" => $member
        ));
    }

    /**
     * function ajax like dislike des personnage
     */
    public function collectionAjaxPersonnageVoteAction(Request $request)
    {
        $vote = $request->get('vote');
        $personnageId = $request->get('personnage_id');
        $userId = $request->get('user_id');
        $em = $this->getDoctrine()->getManager();
        $voteSaved = $em->getRepository('BackBundle:PersonnageVote')->findOneBy(['personnageMedia' => $personnageId, 'user' => $userId]);
        if (empty($voteSaved)) {
            $voteSaved = new PersonnageVote();
            $voteSaved->setVote($vote);
            $user = $em->getRepository('UserBundle:User')->find($userId);
            $voteSaved->setUser($user);
            $personnage = $em->getRepository('BackBundle:PersonnageMedia')->find($personnageId);
            $voteSaved->setPersonnageMedia($personnage);
        } else {
            $voteSaved->setVote($vote);
        }
        $em->persist($voteSaved);
        $em->flush();

        $numberLike = 0;
        $entity = $em->getRepository('BackBundle:PersonnageVote')->findBy(array("personnageMedia" => $personnageId, "vote" => 1));
        if ($entity != null)
            $numberLike = count($entity);

        $numberDisLike = 0;
        $entity = $em->getRepository('BackBundle:PersonnageVote')->findBy(array("personnageMedia" => $personnageId, "vote" => 0));
        if ($entity != null)
            $numberDisLike = count($entity);

        $personnage = $em->getRepository('BackBundle:PersonnageMedia')->find($personnageId);
        $personnage->setNombreLike($numberLike);
        $personnage->setNombreDislike($numberDisLike);
        $em->persist($personnage);
        $em->flush();
        return new JsonResponse(
            [
                'numberLike' => $numberLike,
                'numberDislike' => $numberDisLike,
            ]
        );
    }

    public function collectionAjaxBookVoteAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $bookId = $request->get('bookId');
        $book = $em->getRepository('BackBundle:Collectionmedia')->find($bookId);
        $nbrbook = $em->getRepository('BackBundle:BookVotes')->findBy(array( "user" => $this->getUser() , "book" => $bookId));

            if($nbrbook == null ){
                $entity = new BookVotes();
                $entity->setUser($this->getUser());
                $entity->setBook($book);
                $em->persist($entity);
                $em->flush();
            }



        $nbrbook = $em->getRepository('BackBundle:BookVotes')->findBy(array( "book" => $bookId));


        return new JsonResponse(
            [
                'nbrbook' => count($nbrbook)
            ]
        );
    }

    /**
     * function ajax like Beaux des personnage
     */
    public function collectionAjaxPersonnageBeauxAction(Request $request)
    {
        $personnageId = $request->get('personnage_id');
        $em = $this->getDoctrine()->getManager();
        $personnageMedia = $em->getRepository('BackBundle:PersonnageMedia')->find($personnageId);
        $translator = $this->get('translator');

        if ($personnageMedia->getBeauxPersonnages() == 1) {
            $personnageMedia->setBeauxPersonnages(0);

            $message = $translator->trans('btn.add_beaux_personnages');
            $alert = ' <i class="zmdi zmdi-plus" ></i>';

        } else {
            $personnageMedia->setBeauxPersonnages(1);
            $message = $translator->trans('btn.remove_beaux_personnages');
            $alert = '  <i class="zmdi zmdi-minus" ></i> ';

        }
        $em->merge($personnageMedia);
        $em->flush();

        return new JsonResponse(
            [
                'message' => $message,
                'alert' => $alert,

            ]
        );
    }


    /*
     *
     */
    public function collectionEditPersonnageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $personnage = $request->get('personnage');
        $livre = $request->get('id_livre');
        $description = $request->get('description');
        $personnageMedia = $em->getRepository('BackBundle:PersonnageMedia')->find($id);
        /** upload image **/
        if (isset($_FILES['image_personnage'])) {
            $picture = $_FILES['image_personnage']['name'];
            $tmp_name_array = $_FILES['image_personnage']['tmp_name'];
            $size_array = $_FILES['image_personnage']['size'];
            if ($size_array > 0) {
                $extension = pathinfo($picture, PATHINFO_EXTENSION);
                $picture = md5(uniqid()) . "." . $extension;
                move_uploaded_file($tmp_name_array, 'upload/personnage_media/' . $picture);
                $personnageMedia->setImage($picture);
            }
        }
        $personnageMedia->setPersonnage($personnage);
        $personnageMedia->setDescription($description);
        $em->merge($personnageMedia);
        $em->flush();
        $personnagesMedias = $em->getRepository('BackBundle:PersonnageMedia')->findBy(['collectionMedia' => $livre]);
        $ListePersonnageHtml = $this->renderView('MembreBundle:Collection:list_personnage_ajax.html.twig', array(
                'personnageMediaList' => $personnagesMedias
            )
        );
        return new JsonResponse(array(
            'ListePersonnageHtml' => $ListePersonnageHtml
        ));
    }

    /**
     * Delete Personnage Livre
     */
    public function collectionDeletePersonnageAction(PersonnageMedia $personnage)
    {

        if ($personnage != null) {
            $livre = $personnage->getCollectionMedia()->getId();
            $em = $this->getDoctrine()->getManager();
            $em->remove($personnage);
            $em->flush();
        }

        return $this->redirectToRoute('collection_list_personnage', array('livre' => $livre, 'member' => $this->getUser()->getId()));
    }
}
