<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typegallery
 *
 * @ORM\Table(name="type_gallery")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\TypegalleryRepository")
 */
class Typegallery
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
     * @ORM\OneToMany(targetEntity="Gallerys", mappedBy="typegallery"  , fetch="LAZY")
     */
    private $gallerys;

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
     * @return Typegallery
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
     * Constructor
     */
    public function __construct()
    {
        $this->gallerys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add gallery
     *
     * @param \BackBundle\Entity\Gallerys $gallery
     *
     * @return Typegallery
     */
    public function addGallery(\BackBundle\Entity\Gallerys $gallery)
    {
        $this->gallerys[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param \BackBundle\Entity\Gallerys $gallery
     */
    public function removeGallery(\BackBundle\Entity\Gallerys $gallery)
    {
        $this->gallerys->removeElement($gallery);
    }

    /**
     * Get gallerys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGallerys()
    {
        return $this->gallerys;
    }
}
