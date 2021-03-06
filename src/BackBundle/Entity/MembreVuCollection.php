<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MembreVuCollection
 *
 * @ORM\Table(name="membre_vu_collection")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\MembreVuCollectionRepository")
 */
class MembreVuCollection
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
     * @var bool
     *
     * @ORM\Column(name="collection_photo", type="boolean")
     */
    private $collectionPhoto;

    /**
     * @var bool
     *
     * @ORM\Column(name="collection_news", type="boolean")
     */
    private $collectionNews;

    /**
     * @var bool
     *
     * @ORM\Column(name="collection_media", type="boolean")
     */
    private $collectionMedia;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="collectionmedia" , fetch="LAZY")
     * @ORM\JoinColumn(name="member_collection", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $memberCollection;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="collectionmedia" , fetch="LAZY")
     * @ORM\JoinColumn(name="member_emitter", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $memberEmitter;


    /**
     * MembreVuCollection constructor.
     */
    public function __construct()
    {
        $this->collectionMedia = true ;
        $this->collectionNews = true ;
        $this->collectionPhoto = true ;
    }

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
     * Set collectionPhoto
     *
     * @param boolean $collectionPhoto
     *
     * @return MembreVuCollection
     */
    public function setCollectionPhoto($collectionPhoto)
    {
        $this->collectionPhoto = $collectionPhoto;

        return $this;
    }

    /**
     * Get collectionPhoto
     *
     * @return boolean
     */
    public function getCollectionPhoto()
    {
        return $this->collectionPhoto;
    }

    /**
     * Set collectionNews
     *
     * @param boolean $collectionNews
     *
     * @return MembreVuCollection
     */
    public function setCollectionNews($collectionNews)
    {
        $this->collectionNews = $collectionNews;

        return $this;
    }

    /**
     * Get collectionNews
     *
     * @return boolean
     */
    public function getCollectionNews()
    {
        return $this->collectionNews;
    }

    /**
     * Set collectionMedia
     *
     * @param boolean $collectionMedia
     *
     * @return MembreVuCollection
     */
    public function setCollectionMedia($collectionMedia)
    {
        $this->collectionMedia = $collectionMedia;

        return $this;
    }

    /**
     * Get collectionMedia
     *
     * @return boolean
     */
    public function getCollectionMedia()
    {
        return $this->collectionMedia;
    }

    /**
     * Set memberCollection
     *
     * @param \UserBundle\Entity\User $memberCollection
     *
     * @return MembreVuCollection
     */
    public function setMemberCollection(\UserBundle\Entity\User $memberCollection = null)
    {
        $this->memberCollection = $memberCollection;

        return $this;
    }

    /**
     * Get memberCollection
     *
     * @return \UserBundle\Entity\User
     */
    public function getMemberCollection()
    {
        return $this->memberCollection;
    }

    /**
     * Set memberEmitter
     *
     * @param \UserBundle\Entity\User $memberEmitter
     *
     * @return MembreVuCollection
     */
    public function setMemberEmitter(\UserBundle\Entity\User $memberEmitter = null)
    {
        $this->memberEmitter = $memberEmitter;

        return $this;
    }

    /**
     * Get memberEmitter
     *
     * @return \UserBundle\Entity\User
     */
    public function getMemberEmitter()
    {
        return $this->memberEmitter;
    }
}
