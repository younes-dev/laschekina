<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collectionmedia
 *
 * @ORM\Table(name="collection_media")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\CollectionmediaRepository")
 */
class Collectionmedia
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
     * @ORM\Column(name="type", type="string", length=255 , nullable=true)
     */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type_media", type="string", length=255 , nullable=true)
     */
    private $typeMedia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status_media", type="string", length=255 , nullable=true)
     */
    private $statusMedia;

    /**
     * @var string
     *
     * @ORM\Column(name="mode_media", type="string", length=255 , nullable=true)
     */
    private $modeMedia;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;
    /**
     * @var string
     *
     * @ORM\Column(name="sourcevideo", type="text", nullable=true)
     */
    private $sourcevideo;
   /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

  /**
     * @var string
     *
     * @ORM\Column(name="pathBook", type="string", length=255, nullable=true)
     */
    private $pathBook;

 
  /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255 , nullable=true)
     */
    private $title;
    
  /**
     * @var string
     *
     * @ORM\Column(name="contenu_book", type="text" , nullable=true)
     */
    private $contenuBook;
    
  /**
     * @var string
     *
     * @ORM\Column(name="description", type="text" , nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="description_court", type="text", nullable=true)
     */
    private $descriptionCourt;

   /**
     * @var bool
     *
     * @ORM\Column(name="show_page_banniere", type="boolean" , nullable=true)
     */
    private $showPageBanniere;
   /**
     * @var bool
     *
     * @ORM\Column(name="show_page_home", type="boolean" , nullable=true)
     */
    private $showPageHome;

    /**
     * @var string
     *
     * @ORM\Column(name="edition_book", type="string", length=255, nullable=true)
     */
    private $editionBook;

   
    /**
     * @var float
     *
     * @ORM\Column(name="price_book", type="float", nullable=true)
     */
    private $priceBook;

    /**
     * @var string
     *
     * @ORM\Column(name="date_sortie", type="string", length=255, nullable=true)
     */
    private $dateSortie;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="collectionmedia" , fetch="LAZY")
     * @ORM\JoinColumn(name="member", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $member;

  /**
     * @ORM\OneToMany(targetEntity="Payments", cascade={"persist"}, fetch="LAZY" , mappedBy="book" )
     */
    protected $payment;



  /**
     * @ORM\OneToMany(targetEntity="BookImagesBD",  mappedBy="book"  ,  cascade={"persist", "remove"} )
     */
    protected $bookImages;

  /**
     * @ORM\OneToMany(targetEntity="BookVotes",  mappedBy="book"  ,  cascade={"persist", "remove"} )
     */
    protected $bookVotes;

    /**
     * @ORM\OneToMany(targetEntity="PersonnageMedia", mappedBy="collectionMedia", cascade={"persist", "remove"})
     */
    private $personnagesMedia;
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
     * Set type
     *
     * @param string $type
     *
     * @return collectionmedia
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return collectionmedia
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return collectionmedia
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set member
     *
     * @param \UserBundle\Entity\User $member
     *
     * @return Collectionmedia
     */
    public function setMember(\UserBundle\Entity\User $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \UserBundle\Entity\User
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Collectionmedia
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Collectionmedia
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
     * Set sourcevideo
     *
     * @param string $sourcevideo
     *
     * @return Collectionmedia
     */
    public function setSourcevideo($sourcevideo)
    {
        $this->sourcevideo = $sourcevideo;

        return $this;
    }

    /**
     * Get sourcevideo
     *
     * @return string
     */
    public function getSourcevideo()
    {
        return $this->sourcevideo;
    }

    /**
     * Set typeMedia
     *
     * @param string $typeMedia
     *
     * @return Collectionmedia
     */
    public function setTypeMedia($typeMedia)
    {
        $this->typeMedia = $typeMedia;

        return $this;
    }

    /**
     * Get typeMedia
     *
     * @return string
     */
    public function getTypeMedia()
    {
        return $this->typeMedia;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Collectionmedia
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set editionBook
     *
     * @param string $editionBook
     *
     * @return Collectionmedia
     */
    public function setEditionBook($editionBook)
    {
        $this->editionBook = $editionBook;

        return $this;
    }

    /**
     * Get editionBook
     *
     * @return string
     */
    public function getEditionBook()
    {
        return $this->editionBook;
    }

    /**
     * Set dateSortie
     *
     * @param string $dateSortie
     *
     * @return Collectionmedia
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return string
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set modeMedia
     *
     * @param string $modeMedia
     *
     * @return Collectionmedia
     */
    public function setModeMedia($modeMedia)
    {
        $this->modeMedia = $modeMedia;

        return $this;
    }

    /**
     * Get modeMedia
     *
     * @return string
     */
    public function getModeMedia()
    {
        return $this->modeMedia;
    }
     
    /**
     * Set priceBook
     *
     * @param float $priceBook
     *
     * @return Collectionmedia
     */
    public function setPriceBook($priceBook)
    {
        $this->priceBook = $priceBook;

        return $this;
    }

    /**
     * Get priceBook
     *
     * @return float
     */
    public function getPriceBook()
    {
        return $this->priceBook;
    }

    /**
     * Set pathBook
     *
     * @param string $pathBook
     *
     * @return Collectionmedia
     */
    public function setPathBook($pathBook)
    {
        $this->pathBook = $pathBook;

        return $this;
    }

    /**
     * Get pathBook
     *
     * @return string
     */
    public function getPathBook()
    {
        return $this->pathBook;
    }
      


    /**
     * Add payment
     *
     * @param \BackBundle\Entity\Payments $payment
     *
     * @return Collectionmedia
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
     * Add personnagesMedia
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnagesMedia
     *
     * @return Collectionmedia
     */
    public function addPersonnagesMedia(\BackBundle\Entity\PersonnageMedia $personnagesMedia)
    {
        $this->personnagesMedia[] = $personnagesMedia;
        return $this;
    }
    /**
     * Remove personnagesMedia
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnagesMedia
     */
    public function removePersonnagesMedia(\BackBundle\Entity\PersonnageMedia $personnagesMedia)
    {
        $this->personnagesMedia->removeElement($personnagesMedia);
    }
    /**
     * Get personnagesMedia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonnagesMedia()
    {
        return $this->personnagesMedia;
    }
    /**
     * Set showPageBanniere
     *
     * @param boolean $showPageBanniere
     *
     * @return Collectionmedia
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
     * Set contenuBook
     *
     * @param string $contenuBook
     *
     * @return Collectionmedia
     */
    public function setContenuBook($contenuBook)
    {
        $this->contenuBook = $contenuBook;

        return $this;
    }

    /**
     * Get contenuBook
     *
     * @return string
     */
    public function getContenuBook()
    {
        return $this->contenuBook;
    }

    /**
     * Set descriptionCourt
     *
     * @param string $descriptionCourt
     *
     * @return Collectionmedia
     */
    public function setDescriptionCourt($descriptionCourt)
    {
        $this->descriptionCourt = $descriptionCourt;

        return $this;
    }

    /**
     * Get descriptionCourt
     *
     * @return string
     */
    public function getDescriptionCourt()
    {
        return $this->descriptionCourt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->payment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bookImages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->personnagesMedia = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setShowPageBanniere(0);
        $this->setShowPageHome(0);
    }

    /**
     * Add bookImage
     *
     * @param \BackBundle\Entity\BookImagesBD $bookImage
     *
     * @return Collectionmedia
     */
    public function addBookImage(\BackBundle\Entity\BookImagesBD $bookImage)
    {
        $this->bookImages[] = $bookImage;

        return $this;
    }

    /**
     * Remove bookImage
     *
     * @param \BackBundle\Entity\BookImagesBD $bookImage
     */
    public function removeBookImage(\BackBundle\Entity\BookImagesBD $bookImage)
    {
        $this->bookImages->removeElement($bookImage);
    }

    /**
     * Get bookImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookImages()
    {
        return $this->bookImages;
    }

    /**
     * Set statusMedia
     *
     * @param string $statusMedia
     *
     * @return Collectionmedia
     */
    public function setStatusMedia($statusMedia)
    {
        $this->statusMedia = $statusMedia;

        return $this;
    }

    /**
     * Get statusMedia
     *
     * @return string
     */
    public function getStatusMedia()
    {
        return $this->statusMedia;
    }

    /**
     * Set showPageHome
     *
     * @param boolean $showPageHome
     *
     * @return Collectionmedia
     */
    public function setShowPageHome($showPageHome)
    {
        $this->showPageHome = $showPageHome;

        return $this;
    }

    /**
     * Get showPageHome
     *
     * @return boolean
     */
    public function getShowPageHome()
    {
        return $this->showPageHome;
    }

    /**
     * Add bookVote
     *
     * @param \BackBundle\Entity\BookVotes $bookVote
     *
     * @return Collectionmedia
     */
    public function addBookVote(\BackBundle\Entity\BookVotes $bookVote)
    {
        $this->bookVotes[] = $bookVote;

        return $this;
    }

    /**
     * Remove bookVote
     *
     * @param \BackBundle\Entity\BookVotes $bookVote
     */
    public function removeBookVote(\BackBundle\Entity\BookVotes $bookVote)
    {
        $this->bookVotes->removeElement($bookVote);
    }

    /**
     * Get bookVotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookVotes()
    {
        return $this->bookVotes;
    }
}
