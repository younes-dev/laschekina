<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentaireMedia
 *
 * @ORM\Table(name="commentaire_media")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\CommentaireMediaRepository")
 */
class CommentaireMedia
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
 

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=true)
     */
    private $datecreate;
    /**
     * @ORM\ManyToOne(targetEntity="Medias", inversedBy="commentaire" , fetch="LAZY")
     * @ORM\JoinColumn(name="commentaire_media", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $media;

   /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="commentaire" , fetch="LAZY")
     * @ORM\JoinColumn(name="commentaire_user", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $users;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public  function  __construct () { $this->setDatecreate(new \DateTime()); }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return CommentaireMedia
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
     * Set datecreate
     *
     * @param \DateTime $datecreate
     *
     * @return CommentaireMedia
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
     * Set media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return CommentaireMedia
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

  
 

    /**
     * Set users
     *
     * @param \UserBundle\Entity\User $users
     *
     * @return CommentaireMedia
     */
    public function setUsers(\UserBundle\Entity\User $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \UserBundle\Entity\User
     */
    public function getUsers()
    {
        return $this->users;
    }

   
}
