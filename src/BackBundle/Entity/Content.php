<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content
 *
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\ContentRepository")
 */
class Content
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
     * @var string
     *
     * @ORM\Column(name="title",  type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="languge", type="string", length=255, nullable=true)
     */
    private $languge;
    /**
     * @ORM\ManyToOne(targetEntity="TypeContent", inversedBy="content" , fetch="LAZY")
     * @ORM\JoinColumn(name="content_type", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $contenttype;


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
     * Set languge
     *
     * @param string $languge
     *
     * @return Content
     */
    public function setLanguge($languge)
    {
        $this->languge = $languge;

        return $this;
    }

    /**
     * Get languge
     *
     * @return string
     */
    public function getLanguge()
    {
        return $this->languge;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Content
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
     * Set contenttype
     *
     * @param \BackBundle\Entity\TypeContent $contenttype
     *
     * @return Content
     */
    public function setContenttype(\BackBundle\Entity\TypeContent $contenttype = null)
    {
        $this->contenttype = $contenttype;

        return $this;
    }

    /**
     * Get contenttype
     *
     * @return \BackBundle\Entity\TypeContent
     */
    public function getContenttype()
    {
        return $this->contenttype;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Content
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
}
