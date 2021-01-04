<?php
/**
 * Created by PhpStorm.
 * User: Mouadh
 * Date: 15/01/2017
 * Time: 12:50
 */

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\UserRepository")
 *
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255,nullable = true)
     */
    private $adresse;
    /**
     * @var string
     *
     * @ORM\Column(name="ca_pub", type="string", length=550,nullable = true)
     */
    private $caPub;
  /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255,nullable = true)
     */
    private $latitude;
    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255,nullable = true)
     */
    private $longitude;
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255,nullable = true)
     */
    private $ville;
    /**
     * @var string
     *
     * @ORM\Column(name="type_compte", type="string", length=255,nullable = true)
     */
    private $typecompte;
    /**
     * @var string
     *
     * @ORM\Column(name="nomentreprise", type="string", length=255,nullable = true)
     */
    private $nomentreprise;
    /**
     * @var float
     *
     * @ORM\Column(name="numseret", type="float",nullable = true)
     */
    private $numseret;


    /**
     * @var string
     *
     * @ORM\Column(name="picture_profil", type="string",nullable = true)
     *
     */
    private $pictureprofil;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_cover", type="string",nullable = true)
     */
    private $picturecover;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=255,nullable = true)
     */
    private $codepostal;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255,nullable = true)
     */
    private $nom;
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255,nullable = true)
     */
    private $prenom;
    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255,nullable = true)
     */
    private $telephone;
    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255,nullable = true)
     */
    private $twitter;
    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255,nullable = true)
     */
    private $facebook;
    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string", length=255,nullable = true)
     */
    private $instagram;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text",nullable = true)
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255,nullable = true)
     */
    private $pays;
    /**
     * @var string
     *
     * @ORM\Column(name="color_emotion_card", type="string", length=255,nullable = true)
     */
    private $coloremotioncard;
    /**
     * @var  boolean
     * @ORM\Column(name="status_list_amis", type="boolean" ,nullable = true)
     */
    private $listamis;
      /**
     * @var  boolean
     * @ORM\Column(name="fan", type="boolean" ,nullable = true)
     */
    private $fan;

    /**
     * @var  boolean
     * @ORM\Column(name="etat_messenger", type="boolean"  ,nullable = true)
     */
    private $etatMessenger;
    /**
     * @var  boolean
     * @ORM\Column(name="show_posotion", type="boolean"  ,nullable = true)
     */
    private $showposotion;
  
    /**
     * @var  boolean
     * @ORM\Column(name="show_in_home", type="boolean"  ,nullable = true)
     */
    private $showInHome;

    /**
     * @var  boolean
     * @ORM\Column(name="show_vip_box_in_home", type="boolean"  ,nullable = true)
     */
    private $showVipBoxInHome;

    /**
     * @var  boolean
     * @ORM\Column(name="show_map_photo", type="boolean"  ,nullable = true)
     */
    private $showmapphoto;
    /**
     * @var integer
     *
     * @ORM\Column(name="number_vu", type="integer" ,nullable = true)
     */
    private $numbervu;

    /**
     * @var date
     *
     * @ORM\Column(name="date_naissance", type="date", nullable = true)
     */
    private $datenaissance;

    /**
     * @ORM\ManyToMany(targetEntity="\BackBundle\Entity\Medias", cascade={"persist"} , mappedBy="users" )
     */
    protected $medias;
 


    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\CommentaireMedia", cascade={"persist"} , mappedBy="users" )
     */
    protected $commentaire;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Payments", cascade={"persist"} , mappedBy="user" )
     */
    protected $payment;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\LikeMedia", cascade={"persist"} , mappedBy="member" )
     */
    protected $likemedia;

  /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Humeur", cascade={"persist"} , mappedBy="member" )
     */
    protected $humeur;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Rendezvous", cascade={"persist"} , mappedBy="vip" )
     */
    protected $rendezvous;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Collectionmedia", cascade={"persist"} , mappedBy="member" )
     */
    protected $collectionmedia;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Messaging", cascade={"persist"} , mappedBy="useremitter" )
     */
    protected $messagesent;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Messaging", cascade={"persist"} , mappedBy="userreceiver" )
     */
    protected $messagereceived;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Box", cascade={"persist"},  mappedBy="membre" )
     */
    protected $boxmembre;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Magazinegallery", cascade={"persist"},  mappedBy="usercreate" )
     */
    protected $magazinegallery;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Box", cascade={"persist"},  mappedBy="vip" )
     */
    protected $boxvip;

    /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Crossword", cascade={"persist"},  mappedBy="creator" )
     */
    protected $crossword;

  /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Listamis", cascade={"persist"},  mappedBy="userEmitter" )
     */
    protected $ListamisEmitter;

  /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Listamis", cascade={"persist"},  mappedBy="userReceiver" )
     */
    protected $ListamisReceiver;

        /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\PersonnageMedia", cascade={"persist"}, mappedBy="user")
     */
    private $personnageVotes;

     /**
     * @ORM\OneToMany(targetEntity="\BackBundle\Entity\Medias", mappedBy="user")
     */
    private $events;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=true)
     */
    private $datecreate;

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse ( $adresse )
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse ()
    {
        return $this->adresse;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return User
     */
    public function setVille ( $ville )
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille ()
    {
        return $this->ville;
    }

    /**
     * Set typecompte
     *
     * @param string $typecompte
     *
     * @return User
     */
    public function setTypecompte ( $typecompte )
    {
        $this->typecompte = $typecompte;

        return $this;
    }

    /**
     * Get typecompte
     *
     * @return string
     */
    public function getTypecompte ()
    {
        return $this->typecompte;
    }

    /**
     * Set pictureprofil
     *
     * @param string $pictureprofil
     *
     * @return User
     */
    public function setPictureprofil ( $pictureprofil )
    {
        $this->pictureprofil = $pictureprofil;

        return $this;
    }

    /**
     * Get pictureprofil
     *
     * @return string
     */
    public function getPictureprofil ()
    {
        return $this->pictureprofil;
    }

    /**
     * Set picturecover
     *
     * @param string $picturecover
     *
     * @return User
     */
    public function setPicturecover ( $picturecover )
    {
        $this->picturecover = $picturecover;

        return $this;
    }

    /**
     * Get picturecover
     *
     * @return string
     */
    public function getPicturecover ()
    {
        return $this->picturecover;
    }

    /**
     * Set codepostal
     *
     * @param string $codepostal
     *
     * @return User
     */
    public function setCodepostal ( $codepostal )
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    /**
     * Get codepostal
     *
     * @return string
     */
    public function getCodepostal ()
    {
        return $this->codepostal;
    }


    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return User
     */
    public function setTelephone ( $telephone )
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone ()
    {
        return $this->telephone;
    }

    /**
     * Set datenaissance
     *
     * @param \DateTime $datenaissance
     *
     * @return User
     */
    public function setDatenaissance ( $datenaissance )
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    /**
     * Get datenaissance
     *
     * @return \DateTime
     */
    public function getDatenaissance ()
    {
        return $this->datenaissance;
    }

    /**
     * Add media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return User
     */
    public function addMedia ( \BackBundle\Entity\Medias $media )
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \BackBundle\Entity\Medias $media
     */
    public function removeMedia ( \BackBundle\Entity\Medias $media )
    {
        $this->medias->removeElement ( $media );
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias ()
    {
        return $this->medias;
    }


    /**
     * Add commentaire
     *
     * @param \BackBundle\Entity\CommentaireMedia $commentaire
     *
     * @return User
     */
    public function addCommentaire ( \BackBundle\Entity\CommentaireMedia $commentaire )
    {
        $this->commentaire[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \BackBundle\Entity\CommentaireMedia $commentaire
     */
    public function removeCommentaire ( \BackBundle\Entity\CommentaireMedia $commentaire )
    {
        $this->commentaire->removeElement ( $commentaire );
    }

    /**
     * Get commentaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaire ()
    {
        return $this->commentaire;
    }

    /**
     * Add collectionmedia
     *
     * @param \BackBundle\Entity\Collectionmedia $collectionmedia
     *
     * @return User
     */
    public function addCollectionmedia ( \BackBundle\Entity\Collectionmedia $collectionmedia )
    {
        $this->collectionmedia[] = $collectionmedia;

        return $this;
    }

    /**
     * Remove collectionmedia
     *
     * @param \BackBundle\Entity\Collectionmedia $collectionmedia
     */
    public function removeCollectionmedia ( \BackBundle\Entity\Collectionmedia $collectionmedia )
    {
        $this->collectionmedia->removeElement ( $collectionmedia );
    }

    /**
     * Get collectionmedia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollectionmedia ()
    {
        return $this->collectionmedia;
    }

    /**
     * Set numbervu
     *
     * @param integer $numbervu
     *
     * @return User
     */
    public function setNumbervu ( $numbervu )
    {
        $this->numbervu = $numbervu;

        return $this;
    }

    /**
     * Get numbervu
     *
     * @return integer
     */
    public function getNumbervu ()
    {
        return $this->numbervu;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
     */
    public function setDescription ( $description )
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription ()
    {
        return $this->description;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return User
     */
    public function setTwitter ( $twitter )
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter ()
    {
        return $this->twitter;
    }


    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return User
     */
    public function setFacebook ( $facebook )
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook ()
    {
        return $this->facebook;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     *
     * @return User
     */
    public function setInstagram ( $instagram )
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string
     */
    public function getInstagram ()
    {
        return $this->instagram;
    }

   
    /**
     * Add boxvip
     *
     * @param \BackBundle\Entity\Box $boxvip
     *
     * @return User
     */
    public function addBoxvip ( \BackBundle\Entity\Box $boxvip )
    {
        $this->boxvip[] = $boxvip;

        return $this;
    }

    /**
     * Remove boxvip
     *
     * @param \BackBundle\Entity\Box $boxvip
     */
    public function removeBoxvip ( \BackBundle\Entity\Box $boxvip )
    {
        $this->boxvip->removeElement ( $boxvip );
    }

    /**
     * Get boxvip
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBoxvip ()
    {
        return $this->boxvip;
    }



    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom ( $nom )
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom ()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom ( $prenom )
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom ()
    {
        return $this->prenom;
    }

    /**
     * Set datecreate
     *
     * @param \DateTime $datecreate
     *
     * @return Box
     */
    public function setDatecreate($datecreate)
    {
        $this->datecreate = $datecreate;

        return $this;
    }

    /**
     * Get datecreate
     *
     * @return \DateTime
     */
    public function getDatecreate()
    {
        return $this->datecreate;
    }

    public function __construct ()
    {
        parent::__construct ();
        // your own logic
        $this->setListamis(1); 

        //show_vip_box == 0 <=> affiche vip de box or show_vip_box == 1 afficher VIP par defaut
        $this->setShowVipBoxInHome(0);
	    $this->setEtatMessenger(0);
	    $this->setShowInHome(0);
        $this->setShowposotion(1);
        $this->setShowmapphoto(1);
        $this->setFan(false);
        $this->setDatecreate(new \DateTime());
        $this->payment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new ArrayCollection();

    }

    public function __toString ()
    {
        return $this->prenom . " " . $this->nom;
    }
    
    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return User
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set coloremotioncard
     *
     * @param string $coloremotioncard
     *
     * @return User
     */
    public function setColoremotioncard($coloremotioncard)
    {
        $this->coloremotioncard = $coloremotioncard;

        return $this;
    }

    /**
     * Get coloremotioncard
     *
     * @return string
     */
    public function getColoremotioncard()
    {
        return $this->coloremotioncard;
    }

    /**
     * Set fan
     *
     * @param boolean $fan
     *
     * @return User
     */
    public function setFan($fan)
    {
        $this->fan = $fan;

        return $this;
    }

    /**
     * Get fan
     *
     * @return boolean
     */
    public function getFan()
    {
        return $this->fan;
    }



    /**
     * Set listamis
     *
     * @param boolean $listamis
     *
     * @return User
     */
    public function setListamis($listamis)
    {
        $this->listamis = $listamis;

        return $this;
    }

    /**
     * Get listamis
     *
     * @return boolean
     */
    public function getListamis()
    {
        return $this->listamis;
    }


    /**
     * Set etatMessenger
     *
     * @param boolean $etatMessenger
     *
     * @return User
     */
    public function setEtatMessenger($etatMessenger)
    {
        $this->etatMessenger = $etatMessenger;

        return $this;
    }

    /**
     * Get etatMessenger
     *
     * @return boolean
     */
    public function getEtatMessenger()
    {
        return $this->etatMessenger;
    }

    /**
     * Add messagesent
     *
     * @param \BackBundle\Entity\Messaging $messagesent
     *
     * @return User
     */
    public function addMessagesent(\BackBundle\Entity\Messaging $messagesent)
    {
        $this->messagesent[] = $messagesent;

        return $this;
    }

    /**
     * Remove messagesent
     *
     * @param \BackBundle\Entity\Messaging $messagesent
     */
    public function removeMessagesent(\BackBundle\Entity\Messaging $messagesent)
    {
        $this->messagesent->removeElement($messagesent);
    }

    /**
     * Get messagesent
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessagesent()
    {
        return $this->messagesent;
    }

    /**
     * Add messagereceived
     *
     * @param \BackBundle\Entity\Messaging $messagereceived
     *
     * @return User
     */
    public function addMessagereceived(\BackBundle\Entity\Messaging $messagereceived)
    {
        $this->messagereceived[] = $messagereceived;

        return $this;
    }

    /**
     * Remove messagereceived
     *
     * @param \BackBundle\Entity\Messaging $messagereceived
     */
    public function removeMessagereceived(\BackBundle\Entity\Messaging $messagereceived)
    {
        $this->messagereceived->removeElement($messagereceived);
    }

    /**
     * Get messagereceived
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessagereceived()
    {
        return $this->messagereceived;
    }

    /**
     * Add listamisEmitter
     *
     * @param \BackBundle\Entity\Listamis $listamisEmitter
     *
     * @return User
     */
    public function addListamisEmitter(\BackBundle\Entity\Listamis $listamisEmitter)
    {
        $this->ListamisEmitter[] = $listamisEmitter;

        return $this;
    }

    /**
     * Remove listamisEmitter
     *
     * @param \BackBundle\Entity\Listamis $listamisEmitter
     */
    public function removeListamisEmitter(\BackBundle\Entity\Listamis $listamisEmitter)
    {
        $this->ListamisEmitter->removeElement($listamisEmitter);
    }

    /**
     * Get listamisEmitter
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListamisEmitter()
    {
        return $this->ListamisEmitter;
    }

    /**
     * Add listamisReceiver
     *
     * @param \BackBundle\Entity\Listamis $listamisReceiver
     *
     * @return User
     */
    public function addListamisReceiver(\BackBundle\Entity\Listamis $listamisReceiver)
    {
        $this->ListamisReceiver[] = $listamisReceiver;

        return $this;
    }

    /**
     * Remove listamisReceiver
     *
     * @param \BackBundle\Entity\Listamis $listamisReceiver
     */
    public function removeListamisReceiver(\BackBundle\Entity\Listamis $listamisReceiver)
    {
        $this->ListamisReceiver->removeElement($listamisReceiver);
    }

    /**
     * Get listamisReceiver
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListamisReceiver()
    {
        return $this->ListamisReceiver;
    }


    /**
     * Add magazinegallery
     *
     * @param \BackBundle\Entity\Magazinegallery $magazinegallery
     *
     * @return User
     */
    public function addMagazinegallery(\BackBundle\Entity\Magazinegallery $magazinegallery)
    {
        $this->magazinegallery[] = $magazinegallery;

        return $this;
    }

    /**
     * Remove magazinegallery
     *
     * @param \BackBundle\Entity\Magazinegallery $magazinegallery
     */
    public function removeMagazinegallery(\BackBundle\Entity\Magazinegallery $magazinegallery)
    {
        $this->magazinegallery->removeElement($magazinegallery);
    }

    /**
     * Get magazinegallery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMagazinegallery()
    {
        return $this->magazinegallery;
    }

    /**
     * Add crossword
     *
     * @param \BackBundle\Entity\Crossword $crossword
     *
     * @return User
     */
    public function addCrossword(\BackBundle\Entity\Crossword $crossword)
    {
        $this->crossword[] = $crossword;

        return $this;
    }

    /**
     * Remove crossword
     *
     * @param \BackBundle\Entity\Crossword $crossword
     */
    public function removeCrossword(\BackBundle\Entity\Crossword $crossword)
    {
        $this->crossword->removeElement($crossword);
    }

    /**
     * Get crossword
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCrossword()
    {
        return $this->crossword;
    }

    /**
     * Add rendezvous
     *
     * @param \BackBundle\Entity\Rendezvous $rendezvous
     *
     * @return User
     */
    public function addRendezvous(\BackBundle\Entity\Rendezvous $rendezvous)
    {
        $this->rendezvous[] = $rendezvous;

        return $this;
    }

    /**
     * Remove rendezvous
     *
     * @param \BackBundle\Entity\Rendezvous $rendezvous
     */
    public function removeRendezvous(\BackBundle\Entity\Rendezvous $rendezvous)
    {
        $this->rendezvous->removeElement($rendezvous);
    }

    /**
     * Get rendezvous
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRendezvous()
    {
        return $this->rendezvous;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    

    /**
     * Set showInHome
     *
     * @param boolean $showInHome
     *
     * @return User
     */
    public function setShowInHome($showInHome)
    {
        $this->showInHome = $showInHome;

        return $this;
    }

    /**
     * Get showInHome
     *
     * @return boolean
     */
    public function getShowInHome()
    {
        return $this->showInHome;
    }


    /**
     * Set showposotion
     *
     * @param boolean $showposotion
     *
     * @return User
     */
    public function setShowposotion($showposotion)
    {
        $this->showposotion = $showposotion;

        return $this;
    }

    /**
     * Get showposotion
     *
     * @return boolean
     */
    public function getShowposotion()
    {
        return $this->showposotion;
    }

    /**
     * Set showmapphoto
     *
     * @param boolean $showmapphoto
     *
     * @return User
     */
    public function setShowmapphoto($showmapphoto)
    {
        $this->showmapphoto = $showmapphoto;

        return $this;
    }

    /**
     * Get showmapphoto
     *
     * @return boolean
     */
    public function getShowmapphoto()
    {
        return $this->showmapphoto;
    }

    /**
     * Add humeur
     *
     * @param \BackBundle\Entity\Humeur $humeur
     *
     * @return User
     */
    public function addHumeur(\BackBundle\Entity\Humeur $humeur)
    {
        $this->humeur[] = $humeur;

        return $this;
    }

    /**
     * Remove humeur
     *
     * @param \BackBundle\Entity\Humeur $humeur
     */
    public function removeHumeur(\BackBundle\Entity\Humeur $humeur)
    {
        $this->humeur->removeElement($humeur);
    }

    /**
     * Get humeur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHumeur()
    {
        return $this->humeur;
    }
 
 
  

    /**
     * Add payment
     *
     * @param \BackBundle\Entity\Payments $payment
     *
     * @return User
     */
    public function addPayment(\BackBundle\Entity\Payments $payment)
    {
        $this->payment[] = $payment;

        return $this;
    }

    /**
     * Remove payment
     *
     * @param \BackBundle\Entity\Payments $payment
     */
    public function removePayment(\BackBundle\Entity\Payments $payment)
    {
        $this->payment->removeElement($payment);
    }

    /**
     * Get payment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayment()
    {
        return $this->payment;
    }
    /**
     * Add personnageVote
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnageVote
     *
     * @return User
     */
    public function addPersonnageVote(\BackBundle\Entity\PersonnageMedia $personnageVote)
    {
        $this->personnageVotes[] = $personnageVote;
        return $this;
    }
    /**
     * Remove personnageVote
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnageVote
     */
    public function removePersonnageVote(\BackBundle\Entity\PersonnageMedia $personnageVote)
    {
        $this->personnageVotes->removeElement($personnageVote);
    }
    /**
     * Get personnageVotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonnageVotes()
    {
        return $this->personnageVotes;
    }
    /**
     * @return string
     */
    public function getNomentreprise()
    {
        return $this->nomentreprise;
    }
    /**
     * @param string $nomentreprise
     */
    public function setNomentreprise($nomentreprise)
    {
        $this->nomentreprise = $nomentreprise;
    }
    /**
     * @return float
     */
    public function getNumseret()
    {
        return $this->numseret;
    }
    /**
     * @param float $numseret
     */
    public function setNumseret($numseret)
    {
        $this->numseret = $numseret;
    }
    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }
    /**
     * @param mixed $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * Set showVipBoxInHome
     *
     * @param boolean $showVipBoxInHome
     *
     * @return User
     */
    public function setShowVipBoxInHome($showVipBoxInHome)
    {
        $this->showVipBoxInHome = $showVipBoxInHome;

        return $this;
    }

    /**
     * Get showVipBoxInHome
     *
     * @return boolean
     */
    public function getShowVipBoxInHome()
    {
        return $this->showVipBoxInHome;
    }

    /**
     * Add event
     *
     * @param \BackBundle\Entity\Medias $event
     *
     * @return User
     */
    public function addEvent(\BackBundle\Entity\Medias $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \BackBundle\Entity\Medias $event
     */
    public function removeEvent(\BackBundle\Entity\Medias $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Add boxmembre
     *
     * @param \BackBundle\Entity\Box $boxmembre
     *
     * @return User
     */
    public function addBoxmembre(\BackBundle\Entity\Box $boxmembre)
    {
        $this->boxmembre[] = $boxmembre;

        return $this;
    }

    /**
     * Remove boxmembre
     *
     * @param \BackBundle\Entity\Box $boxmembre
     */
    public function removeBoxmembre(\BackBundle\Entity\Box $boxmembre)
    {
        $this->boxmembre->removeElement($boxmembre);
    }

    /**
     * Get boxmembre
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBoxmembre()
    {
        return $this->boxmembre;
    }

    /**
     * Set caPub
     *
     * @param string $caPub
     *
     * @return User
     */
    public function setCaPub($caPub)
    {
        $this->caPub = $caPub;

        return $this;
    }

    /**
     * Get caPub
     *
     * @return string
     */
    public function getCaPub()
    {
        return $this->caPub;
    }
}
