<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gallerys
 *
 * @ORM\Table(name="gallerys")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\GallerysRepository")
 */
class Gallerys
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
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     * @Assert\Image(
     *     minWidth  = 250,
     *     minHeight = 335,
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
     * @ORM\Column(name="cover_picture", type="string", length=255, nullable=true)
     * @Assert\Image(
     *     minWidth  = 1920,
     *     minHeight = 1080,
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *      }
     *     )
     */
    private $coverpicture;
   /**
     * @var string
     *
     * @ORM\Column(name="losange_picture", type="string", length=255, nullable=true)
     */
    private $losangepicture;
  /**
     * @var string
     *
     * @ORM\Column(name="banner_picture", type="string", length=255, nullable=true)
     */
    private $bannerpicture;

    /**
     * @var bool
     *
     * @ORM\Column(name="state", type="boolean")
     */
    private $state;
    /**
     * @ORM\ManyToOne(targetEntity="Typegallery", inversedBy="gallerys" , fetch="LAZY")
     * @ORM\JoinColumn(name="type_gallery", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $typegallery;

    /**
     * @ORM\ManyToOne(targetEntity="Medias", inversedBy="gallerys" , fetch="LAZY")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
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
     * Set picture
     *
     * @param string $picture
     *
     * @return Gallerys
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
     * Set typegallery
     *
     * @param \BackBundle\Entity\Typegallery $typegallery
     *
     * @return Gallerys
     */
    public function setTypegallery(\BackBundle\Entity\Typegallery $typegallery = null)
    {
        $this->typegallery = $typegallery;

        return $this;
    }

    /**
     * Get typegallery
     *
     * @return \BackBundle\Entity\Typegallery
     */
    public function getTypegallery()
    {
        return $this->typegallery;
    }

    /**
     * Set state
     *
     * @param boolean $state
     *
     * @return Gallerys
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set coverpicture
     *
     * @param string $coverpicture
     *
     * @return Gallerys
     */
    public function setCoverpicture($coverpicture)
    {
        $this->coverpicture = $coverpicture;

        return $this;
    }

    /**
     * Get coverpicture
     *
     * @return string
     */
    public function getCoverpicture()
    {
        return $this->coverpicture;
    }

    /**
     * Set losangepicture
     *
     * @param string $losangepicture
     *
     * @return Gallerys
     */
    public function setLosangepicture($losangepicture)
    {
        $this->losangepicture = $losangepicture;

        return $this;
    }

    /**
     * Get losangepicture
     *
     * @return string
     */
    public function getLosangepicture()
    {
        return $this->losangepicture;
    }

    /**
     * Set bannerpicture
     *
     * @param string $bannerpicture
     *
     * @return Gallerys
     */
    public function setBannerpicture($bannerpicture)
    {
        $this->bannerpicture = $bannerpicture;

        return $this;
    }

    /**
     * Get bannerpicture
     *
     * @return string
     */
    public function getBannerpicture()
    {
        return $this->bannerpicture;
    }

    /**
     * Set media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return Gallerys
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
