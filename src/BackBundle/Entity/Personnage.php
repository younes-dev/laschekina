<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personnage
 *
 * @ORM\Table(name="personnage")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\PersonnageRepository")
 */
class Personnage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mot", type="text")
     */
    private $mot;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

   /**
     * @var  boolean
     * @ORM\Column(name="fan", type="boolean" ,nullable = true)
     */
    private $fan;





    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=255, nullable=true)
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur_peau", type="string", length=255, nullable=true)
     */
    private $couleurPeau;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur_cheveux", type="string", length=255, nullable=true)
     */
    private $couleurCheveux;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur_yeux", type="string", length=255, nullable=true)
     */
    private $couleurYeux;

    /**
     * @var string
     *
     * @ORM\Column(name="taille", type="string", length=255, nullable=true)
     */
    private $taille;

    /**
     * @var string
     *
     * @ORM\Column(name="recompense", type="string", length=255, nullable=true)
     */
    private $recompense;

    /**
     * @ORM\OneToMany(targetEntity="PersonnageMedia", cascade={"persist"} , mappedBy="personnage" )
     */
    protected $personnageMedia;


    /**
     * @ORM\ManyToMany(targetEntity="Activity", cascade={"persist"}, fetch="LAZY" , inversedBy="personnage" )
     * @ORM\JoinTable(name="activity_personnage")
     */
    protected $activity;
    /**
     * @ORM\ManyToMany(targetEntity="Category", cascade={"persist"}, fetch="LAZY" , inversedBy="personnage" )
     * @ORM\JoinTable(name="category_personnage")
     */
    protected $category;

    /**
     * @ORM\ManyToMany(targetEntity="Medias", cascade={"persist"}, fetch="LAZY" , mappedBy="personnage" )
     */
    protected $medias;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mot
     *
     * @param string $mot
     *
     * @return Personnage
     */
    public function setMot($mot)
    {
        $this->mot = $mot;

        return $this;
    }

    /**
     * Get mot
     *
     * @return string
     */
    public function getMot()
    {
        return $this->mot;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Personnage
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Personnage
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return Personnage
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Personnage
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Personnage
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return Personnage
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Personnage
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set couleurPeau
     *
     * @param string $couleurPeau
     *
     * @return Personnage
     */
    public function setCouleurPeau($couleurPeau)
    {
        $this->couleurPeau = $couleurPeau;

        return $this;
    }

    /**
     * Get couleurPeau
     *
     * @return string
     */
    public function getCouleurPeau()
    {
        return $this->couleurPeau;
    }

    /**
     * Set couleurCheveux
     *
     * @param string $couleurCheveux
     *
     * @return Personnage
     */
    public function setCouleurCheveux($couleurCheveux)
    {
        $this->couleurCheveux = $couleurCheveux;

        return $this;
    }

    /**
     * Get couleurCheveux
     *
     * @return string
     */
    public function getCouleurCheveux()
    {
        return $this->couleurCheveux;
    }

    /**
     * Set couleurYeux
     *
     * @param string $couleurYeux
     *
     * @return Personnage
     */
    public function setCouleurYeux($couleurYeux)
    {
        $this->couleurYeux = $couleurYeux;

        return $this;
    }

    /**
     * Get couleurYeux
     *
     * @return string
     */
    public function getCouleurYeux()
    {
        return $this->couleurYeux;
    }

    /**
     * Set taille
     *
     * @param string $taille
     *
     * @return Personnage
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille
     *
     * @return string
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set recompense
     *
     * @param string $recompense
     *
     * @return Personnage
     */
    public function setRecompense($recompense)
    {
        $this->recompense = $recompense;

        return $this;
    }

    /**
     * Get recompense
     *
     * @return string
     */
    public function getRecompense()
    {
        return $this->recompense;
    }


    /**
     * Add activity
     *
     * @param \BackBundle\Entity\Activity $activity
     *
     * @return Personnage
     */
    public function addActivity(\BackBundle\Entity\Activity $activity)
    {
        $this->activity[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param \BackBundle\Entity\Activity $activity
     */
    public function removeActivity(\BackBundle\Entity\Activity $activity)
    {
        $this->activity->removeElement($activity);
    }

    /**
     * Get activity
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Add category
     *
     * @param \BackBundle\Entity\Category $category
     *
     * @return Personnage
     */
    public function addCategory(\BackBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \BackBundle\Entity\Category $category
     */
    public function removeCategory(\BackBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function __toString ()
    {
       return $this->nom .' '. $this->prenom ;
    }

    /**
     * Add media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return Personnage
     */
    public function addMedia(\BackBundle\Entity\Medias $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \BackBundle\Entity\Medias $media
     */
    public function removeMedia(\BackBundle\Entity\Medias $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
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
     * Constructor
     */
    public function __construct()
    {
        $this->personnageMedia = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activity = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setFan(0);
    }

    /**
     * Add personnageMedia
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnageMedia
     *
     * @return Personnage
     */
    public function addPersonnageMedia(\BackBundle\Entity\PersonnageMedia $personnageMedia)
    {
        $this->personnageMedia[] = $personnageMedia;

        return $this;
    }

    /**
     * Remove personnageMedia
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnageMedia
     */
    public function removePersonnageMedia(\BackBundle\Entity\PersonnageMedia $personnageMedia)
    {
        $this->personnageMedia->removeElement($personnageMedia);
    }

    /**
     * Get personnageMedia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonnageMedia()
    {
        return $this->personnageMedia;
    }
}
