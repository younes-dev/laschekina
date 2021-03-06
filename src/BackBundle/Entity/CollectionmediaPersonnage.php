<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collectionmedia
 *
 * @ORM\Table(name="collection_media_personnage")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\CollectionmediaRepository")
 */
class CollectionmediaPersonnage
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
     * @ORM\Column(name="type_media", type="string", length=255 , nullable=true)
     */
    private $typeMedia;
 
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
     * @ORM\ManyToOne(targetEntity="PersonnageMedia", inversedBy="collectionmediaPersonnage" )
     * @ORM\JoinColumn(name="personnage_media_id", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $personnageMedia;
 
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
     * Set typeMedia
     *
     * @param string $typeMedia
     *
     * @return CollectionmediaPersonnage
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
     * Set url
     *
     * @param string $url
     *
     * @return CollectionmediaPersonnage
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
     * Set sourcevideo
     *
     * @param string $sourcevideo
     *
     * @return CollectionmediaPersonnage
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
     * Set picture
     *
     * @param string $picture
     *
     * @return CollectionmediaPersonnage
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
     * Set path
     *
     * @param string $path
     *
     * @return CollectionmediaPersonnage
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
     * Set title
     *
     * @param string $title
     *
     * @return CollectionmediaPersonnage
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
     * Set personnageMedia
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnageMedia
     *
     * @return CollectionmediaPersonnage
     */
    public function setPersonnageMedia(\BackBundle\Entity\PersonnageMedia $personnageMedia = null)
    {
        $this->personnageMedia = $personnageMedia;

        return $this;
    }

    /**
     * Get personnageMedia
     *
     * @return \BackBundle\Entity\PersonnageMedia
     */
    public function getPersonnageMedia()
    {
        return $this->personnageMedia;
    }
}
