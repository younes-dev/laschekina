<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Box
 *
 * @ORM\Table(name="box")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\BoxRepository")
 */
class Box
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
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="boxvip" )
     * @ORM\JoinColumn(name="box_vip", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $vip;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="boxmembre" )
     * @ORM\JoinColumn(name="membre_vip", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $membre;
    /**
     * @ORM\ManyToOne(targetEntity="Medias", inversedBy="box" )
     * @ORM\JoinColumn(name="medias_id", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $medias;

  /**
     * @ORM\ManyToOne(targetEntity="PersonnageMedia", inversedBy="box" )
     * @ORM\JoinColumn(name="personnage_media_id", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $personnageMedia;

    /**
     * @var string
     *
     * @ORM\Column(name="typebox", type="string", length=255, nullable=true)
     */
    private $typebox;
   /**
     * @var string
     *
     * @ORM\Column(name="word_search", type="string", length=255, nullable=true)
     */
    private $wordsearch;
  /**
     * @var boolean,
     *
     * @ORM\Column(name="enable", type="boolean", nullable=true)
     */
    private $enable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreate", type="datetime", nullable=true)
     */
    private $datecreate;


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
     * Set datecreate
     *
     * @param \DateTime $datecreate
     *
     * @return Box
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
     * Set vip
     *
     * @param \UserBundle\Entity\User $vip
     *
     * @return Box
     */
    public function setVip(\UserBundle\Entity\User $vip = null)
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * Get vip
     *
     * @return \UserBundle\Entity\User
     */
    public function getVip()
    {
        return $this->vip;
    }

    /**
     * Set membre
     *
     * @param \UserBundle\Entity\User $membre
     *
     * @return Box
     */
    public function setMembre(\UserBundle\Entity\User $membre = null)
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * Get membre
     *
     * @return \UserBundle\Entity\User
     */
    public function getMembre()
    {
        return $this->membre;
    }

    public  function  __construct ()
    {
        $this->setDatecreate(new \DateTime());
        $this->setEnable(1);

    }


    /**
     * Set typebox
     *
     * @param string $typebox
     *
     * @return Box
     */
    public function setTypebox($typebox)
    {
        $this->typebox = $typebox;

        return $this;
    }

    /**
     * Get typebox
     *
     * @return string
     */
    public function getTypebox()
    {
        return $this->typebox;
    }

    /**
     * Set medias
     *
     * @param \BackBundle\Entity\Medias $medias
     *
     * @return Box
     */
    public function setMedias(\BackBundle\Entity\Medias $medias = null)
    {
        $this->medias = $medias;

        return $this;
    }

    /**
     * Get medias
     *
     * @return \BackBundle\Entity\Medias
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set wordsearch
     *
     * @param string $wordsearch
     *
     * @return Box
     */
    public function setWordsearch($wordsearch)
    {
        $this->wordsearch = $wordsearch;

        return $this;
    }

    /**
     * Get wordsearch
     *
     * @return string
     */
    public function getWordsearch()
    {
        return $this->wordsearch;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return Box
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set personnageMedia
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnageMedia
     *
     * @return Box
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
