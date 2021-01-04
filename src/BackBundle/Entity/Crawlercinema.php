<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Crawlercinema
 *
 * @ORM\Table(name="crawler_cinema")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\CrawlercinemaRepository")
 */
class Crawlercinema
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

     /**
     * @var string
     *
     * @ORM\Column(name="channel", type="string", length=255, nullable=true)
     */
     private $channel;
     
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;
  
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreate", type="datetime" , nullable=true)
     */
     private $datecreate;
       /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
     private $shortdescription;
     
     /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     * @Assert\Image(
     *     minWidth  = 600,
     *     minHeight = 600,
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *      }
     *     )
     */

     private $picture;
     
 
    /**
     * @var string
     *
     * @ORM\Column(name="type_picture", type="string", length=255, nullable=true)
     */
     private $typePicture;
     
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime" , nullable=true)
     */
     private $dateStart;

      /**
     * @var string
     *
     * @ORM\Column(name="start_time" , type="string", length=255, nullable=true)
     */
    private $startTime;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255 , nullable=true)
     */
    private $pays;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable", type="boolean", nullable=true)
     */
    private $enable;


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
     * @return Crawlercinema
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
     * Set description
     *
     * @param string $description
     *
     * @return Crawlercinema
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
     * Set url
     *
     * @param string $url
     *
     * @return Crawlercinema
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
     * Set picture
     *
     * @param string $picture
     *
     * @return Crawlercinema
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
     * Set datecreate
     *
     * @param \DateTime $datecreate
     *
     * @return Crawlercinema
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

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return Crawlercinema
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }
    public  function  __construct ()
    {
        
        $this->setStartTime( date("g:i a") );
        $this->setDatecreate(new \DateTime());
        $this->setDateStart(new \DateTime());
        $this->setEnable(1);
        $this->setTypePicture("web");
        
    }


    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Crawlercinema
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
     * Set typePicture
     *
     * @param string $typePicture
     *
     * @return Crawlercinema
     */
    public function setTypePicture($typePicture)
    {
        $this->typePicture = $typePicture;

        return $this;
    }

    /**
     * Get typePicture
     *
     * @return string
     */
    public function getTypePicture()
    {
        return $this->typePicture;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Crawlercinema
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set startTime
     *
     * @param string $startTime
     *
     * @return Crawlercinema
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set shortdescription
     *
     * @param string $shortdescription
     *
     * @return Crawlercinema
     */
    public function setShortdescription($shortdescription)
    {
        $this->shortdescription = $shortdescription;

        return $this;
    }

    /**
     * Get shortdescription
     *
     * @return string
     */
    public function getShortdescription()
    {
        return $this->shortdescription;
    }

    /**
     * Set channel
     *
     * @param string $channel
     *
     * @return Crawlercinema
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
