<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rendezvous
 *
 * @ORM\Table(name="rendezvous")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\RendezvousRepository")
 */
class Rendezvous
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime",nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime" ,nullable=true)
     */
    private $endDate;

/**
     * @var string
     *
     * @ORM\Column(name="start_heur", type="string", length=255 ,nullable=true)
     */
    private $startheur;

    /**
     * @var string
     *
     * @ORM\Column(name="end_heur", type="string", length=255 ,nullable=true)
     */
    private $endheur;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255 ,nullable=true)
     */
    private $title;


    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255 ,nullable=true)
     */
    private $location;


    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=255 ,nullable=true)
     */
    private $lat;


    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=255 ,nullable=true)
     */
    private $lng;

   /**
     * @var string
     *
     * @ORM\Column(name="description", type="text" ,nullable=true)
     */
    private $description;


    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="rendezvous" )
     * @ORM\JoinColumn(name="id_vip", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $vip;

    

    /**
     * @ORM\ManyToOne(targetEntity="Medias", inversedBy="rendezvous" )
     * @ORM\JoinColumn(name="id_media", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $media;

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
     * Set title
     *
     * @param string $title
     *
     * @return Rendezvous
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
     * Set vip
     *
     * @param \UserBundle\Entity\User $vip
     *
     * @return Rendezvous
     */
    public function setVip(\UserBundle\Entity\User $vip = null)
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * Get vip
     *
     * @return \UserBundle\Entity\User
     */
    public function getVip()
    {
        return $this->vip;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Rendezvous
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Rendezvous
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Rendezvous
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
     * Set location
     *
     * @param string $location
     *
     * @return Rendezvous
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set lat
     *
     * @param string $lat
     *
     * @return Rendezvous
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     *
     * @return Rendezvous
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set startheur
     *
     * @param \DateTime $startheur
     *
     * @return Rendezvous
     */
    public function setStartheur($startheur)
    {
        $this->startheur = $startheur;

        return $this;
    }



    /**
     * Get startheur
     *
     * @return string
     */
    public function getStartheur()
    {
        return $this->startheur;
    }

    /**
     * Set endheur
     *
     * @param string $endheur
     *
     * @return Rendezvous
     */
    public function setEndheur($endheur)
    {
        $this->endheur = $endheur;

        return $this;
    }

    /**
     * Get endheur
     *
     * @return string
     */
    public function getEndheur()
    {
        return $this->endheur;
    }

    /**
     * Set media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return Rendezvous
     */
    public function setMedia(\BackBundle\Entity\Medias $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \BackBundle\Entity\Medias
     */
    public function getMedia()
    {
        return $this->media;
    }
}
