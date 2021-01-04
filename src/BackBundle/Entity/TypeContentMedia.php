<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeContentMedia
 *
 * @ORM\Table(name="type_content_media")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\TypeContentMediaRepository")
 */
class TypeContentMedia
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="Medias", mappedBy="typeContentMedia"  )
     */
    private $medias;


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
     * @return TypeContentMedia
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
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();

    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Add media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return TypeContentMedia
     */
    public function addMedia(\BackBundle\Entity\Medias $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \BackBundle\Entity\Medias $media
     */
    public function removeMedia(\BackBundle\Entity\Medias $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }
}
