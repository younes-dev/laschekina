<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeContent
 *
 * @ORM\Table(name="type_content")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\TypeContentRepository")
 */
class TypeContent
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
     * @ORM\OneToMany(targetEntity="Content", mappedBy="contenttype"  , fetch="LAZY")
     */
    private $content;


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
     * @return TypeContent
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
        $this->content = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add content
     *
     * @param \BackBundle\Entity\Content $content
     *
     * @return TypeContent
     */
    public function addContent(\BackBundle\Entity\Content $content)
    {
        $this->content[] = $content;

        return $this;
    }

    /**
     * Remove content
     *
     * @param \BackBundle\Entity\Content $content
     */
    public function removeContent(\BackBundle\Entity\Content $content)
    {
        $this->content->removeElement($content);
    }

    /**
     * Get content
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContent()
    {
        return $this->content;
    }
}
