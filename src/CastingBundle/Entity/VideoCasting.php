<?php

namespace CastingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VideoCasting
 *
 * @ORM\Table(name="video_casting")
 * @ORM\Entity(repositoryClass="CastingBundle\Repository\VideoCastingRepository")
 */
class VideoCasting
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
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="Casting", inversedBy="videoCasting"  )
     * @ORM\JoinColumn(name="id_casting", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $casting;


    /**
     * @ORM\OneToMany(targetEntity="ReponseVideoCasting", cascade={"persist"},  mappedBy="videoCasting" )
     */
    protected $reponseVideoCasting;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reponseVideoCasting = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return VideoCasting
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
     * @return VideoCasting
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
     * Set casting
     *
     * @param \CastingBundle\Entity\Casting $casting
     *
     * @return VideoCasting
     */
    public function setCasting(\CastingBundle\Entity\Casting $casting = null)
    {
        $this->casting = $casting;

        return $this;
    }

    /**
     * Get casting
     *
     * @return \CastingBundle\Entity\Casting
     */
    public function getCasting()
    {
        return $this->casting;
    }

    /**
     * Add reponseVideoCasting
     *
     * @param \CastingBundle\Entity\ReponseVideoCasting $reponseVideoCasting
     *
     * @return VideoCasting
     */
    public function addReponseVideoCasting(\CastingBundle\Entity\ReponseVideoCasting $reponseVideoCasting)
    {
        $this->reponseVideoCasting[] = $reponseVideoCasting;

        return $this;
    }

    /**
     * Remove reponseVideoCasting
     *
     * @param \CastingBundle\Entity\ReponseVideoCasting $reponseVideoCasting
     */
    public function removeReponseVideoCasting(\CastingBundle\Entity\ReponseVideoCasting $reponseVideoCasting)
    {
        $this->reponseVideoCasting->removeElement($reponseVideoCasting);
    }

    /**
     * Get reponseVideoCasting
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReponseVideoCasting()
    {
        return $this->reponseVideoCasting;
    }
}
