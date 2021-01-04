<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Magazinegallery
 *
 * @ORM\Table(name="magazine_gallery")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\MagazinegalleryRepository")
 */
class Magazinegallery
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
     * @ORM\Column(name="state", type="boolean")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     * @Assert\Image(
     *     minWidth = 400,
     *     maxWidth = 450,
     *     minHeight = 500,
     *     maxHeight = 550,
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/gif",
     *      }
     *     )
     */
    private $picture;


    /**
     * @var string
     *
     * @ORM\Column(name="typegallerys", type="string", length=255 , nullable=true )
     */
    private $typegallerys;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreate", type="datetime")
     */
    private $datecreate;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="magazinegallery" )
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $usercreate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct ()
    {
        $this->setDatecreate(new \DateTime()) ;
        $this->setState(1);
    }

    /**
     * Set state
     *
     * @param boolean $state
     *
     * @return Magazinegallery
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
     * Set picture
     *
     * @param string $picture
     *
     * @return Magazinegallery
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
     * Set typegallerys
     *
     * @param string $typegallerys
     *
     * @return Magazinegallery
     */
    public function setTypegallerys($typegallerys)
    {
        $this->typegallerys = $typegallerys;

        return $this;
    }

    /**
     * Get typegallerys
     *
     * @return string
     */
    public function getTypegallerys()
    {
        return $this->typegallerys;
    }

    /**
     * Set datecreate
     *
     * @param \DateTime $datecreate
     *
     * @return Magazinegallery
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
     * Set usercreate
     *
     * @param \UserBundle\Entity\User $usercreate
     *
     * @return Magazinegallery
     */
    public function setUsercreate(\UserBundle\Entity\User $usercreate = null)
    {
        $this->usercreate = $usercreate;

        return $this;
    }

    /**
     * Get usercreate
     *
     * @return \UserBundle\Entity\User
     */
    public function getUsercreate()
    {
        return $this->usercreate;
    }
}
