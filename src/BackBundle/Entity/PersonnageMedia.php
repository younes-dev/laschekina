<?php
namespace BackBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * PersonnageMedia
 *
 * @ORM\Table(name="personnage_media")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\PersonnageMediaRepository")
 */
class PersonnageMedia
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
     * @ORM\Column(name="personnage", type="string", length=255, nullable=true)
     */
    private $personnage;
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
     * @Assert\Image()
     */
    private $image;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_page_banniere", type="boolean" , nullable=true)
     */
    private $showPageBanniere;


    /**
     * @var int
     *
     * @ORM\Column(name="nombre_like", type="integer")
     */
    private $nombreLike ;
    /**
     * @var int
     *
     * @ORM\Column(name="nombre_dislike", type="integer")
     */
    private $nombreDislike ;

    /**
     * @ORM\ManyToOne(targetEntity="Collectionmedia", inversedBy="personnagesMedia")
     */
    protected $collectionMedia;


    /**
     * @ORM\ManyToOne(targetEntity="Box", inversedBy="personnageMedia")
     */
    protected $box;

    /**
     * @ORM\OneToMany(targetEntity="PersonnageVote", cascade={"persist", "remove"}, mappedBy="personnageMedia")
     */
    protected $personnageVotes;

    /**
     * @ORM\OneToMany(targetEntity="CollectionmediaPersonnage", cascade={"persist", "remove"}, mappedBy="personnageMedia")
     */
    private $collectionmediaPersonnage;

    /**
     * @var  boolean
     * @ORM\Column(name="beaux_personnages", type="boolean" ,nullable = true)
     */
    private $beauxPersonnages;
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
     * Set personnage
     *
     * @param string $personnage
     *
     * @return PersonnageMedia
     */
    public function setPersonnage($personnage)
    {
        $this->personnage = $personnage;
        return $this;
    }
    /**
     * Get personnage
     *
     * @return string
     */
    public function getPersonnage()
    {
        return $this->personnage;
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return PersonnageMedia
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
     * @return PersonnageMedia
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
     * Set collectionMedia
     *
     * @param \BackBundle\Entity\Collectionmedia $collectionMedia
     *
     * @return PersonnageMedia
     */
    public function setCollectionMedia(\BackBundle\Entity\Collectionmedia $collectionMedia = null)
    {
        $this->collectionMedia = $collectionMedia;
        return $this;
    }
    /**
     * Get collectionMedia
     *
     * @return \BackBundle\Entity\Collectionmedia
     */
    public function getCollectionMedia()
    {
        return $this->collectionMedia;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personnageVotes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setNombreLike(0);
        $this->setNombreDislike(0);
        $this->setShowPageBanniere(0);
    }
    /**
     * Add personnageVote
     *
     * @param \BackBundle\Entity\PersonnageVote $personnageVote
     *
     * @return PersonnageMedia
     */
    public function addPersonnageVote(\BackBundle\Entity\PersonnageVote $personnageVote)
    {
        $this->personnageVotes[] = $personnageVote;
        return $this;
    }
    /**
     * Remove personnageVote
     *
     * @param \BackBundle\Entity\PersonnageVote $personnageVote
     */
    public function removePersonnageVote(\BackBundle\Entity\PersonnageVote $personnageVote)
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
     * Set nombreLike
     *
     * @param integer $nombreLike
     *
     * @return PersonnageMedia
     */
    public function setNombreLike($nombreLike)
    {
        $this->nombreLike = $nombreLike;
        return $this;
    }
    /**
     * Get nombreLike
     *
     * @return integer
     */
    public function getNombreLike()
    {
        return $this->nombreLike;
    }
    /**
     * Set nombreDislike
     *
     * @param integer $nombreDislike
     *
     * @return PersonnageMedia
     */
    public function setNombreDislike($nombreDislike)
    {
        $this->nombreDislike = $nombreDislike;
        return $this;
    }
    /**
     * Get nombreDislike
     *
     * @return integer
     */
    public function getNombreDislike()
    {
        return $this->nombreDislike;
    }

    /**
     * Set beauxPersonnages
     *
     * @param boolean $beauxPersonnages
     *
     * @return PersonnageMedia
     */
    public function setBeauxPersonnages($beauxPersonnages)
    {
        $this->beauxPersonnages = $beauxPersonnages;

        return $this;
    }

    /**
     * Get beauxPersonnages
     *
     * @return boolean
     */
    public function getBeauxPersonnages()
    {
        return $this->beauxPersonnages;
    }

    /**
     * Add collectionmediaPersonnage
     *
     * @param \BackBundle\Entity\CollectionmediaPersonnage $collectionmediaPersonnage
     *
     * @return PersonnageMedia
     */
    public function addCollectionmediaPersonnage(\BackBundle\Entity\CollectionmediaPersonnage $collectionmediaPersonnage)
    {
        $this->collectionmediaPersonnage[] = $collectionmediaPersonnage;

        return $this;
    }

    /**
     * Remove collectionmediaPersonnage
     *
     * @param \BackBundle\Entity\CollectionmediaPersonnage $collectionmediaPersonnage
     */
    public function removeCollectionmediaPersonnage(\BackBundle\Entity\CollectionmediaPersonnage $collectionmediaPersonnage)
    {
        $this->collectionmediaPersonnage->removeElement($collectionmediaPersonnage);
    }

    /**
     * Get collectionmediaPersonnage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollectionmediaPersonnage()
    {
        return $this->collectionmediaPersonnage;
    }

    /**
     * Set showPageBanniere
     *
     * @param boolean $showPageBanniere
     *
     * @return PersonnageMedia
     */
    public function setShowPageBanniere($showPageBanniere)
    {
        $this->showPageBanniere = $showPageBanniere;

        return $this;
    }

    /**
     * Get showPageBanniere
     *
     * @return boolean
     */
    public function getShowPageBanniere()
    {
        return $this->showPageBanniere;
    }

    /**
     * Set box
     *
     * @param \BackBundle\Entity\Box $box
     *
     * @return PersonnageMedia
     */
    public function setBox(\BackBundle\Entity\Box $box = null)
    {
        $this->box = $box;

        return $this;
    }

    /**
     * Get box
     *
     * @return \BackBundle\Entity\Box
     */
    public function getBox()
    {
        return $this->box;
    }
}
