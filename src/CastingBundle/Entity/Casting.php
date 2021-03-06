<?php

namespace CastingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Casting
 *
 * @ORM\Table(name="casting")
 * @ORM\Entity(repositoryClass="CastingBundle\Repository\CastingRepository")
 */
class Casting
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
     * @ORM\Column(name="scenario", type="string", length=255, nullable=true)
     */
    private $scenario;
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;
    /**
     * @var string
     *
     * @ORM\Column(name="path_pdf", type="string", length=255, nullable=true)
     */
    private $pathPdf;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datesTournageDebut", type="datetime", nullable=true)
     */
    private $datesTournageDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datesTournageEnd", type="datetime", nullable=true)
     */
    private $datesTournageEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="lieuTournage", type="text", nullable=true)
     */
    private $lieuTournage;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="tarif", type="string", length=255, nullable=true)
     */
    private $tarif;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="casting" , fetch="LAZY")
     * @ORM\JoinColumn(name="id_member", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $member;

    /**
     * @ORM\OneToMany(targetEntity="VideoCasting", cascade={"persist"},  mappedBy="casting" )
     */
    protected $videoCasting;

    /**
     * @ORM\OneToMany(targetEntity="CastingReponse", cascade={"persist"},  mappedBy="casting" )
     */
    protected $castingReponse;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->videoCasting = new \Doctrine\Common\Collections\ArrayCollection();
        $this->castingReponse = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set scenario
     *
     * @param string $scenario
     *
     * @return Casting
     */
    public function setScenario($scenario)
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * Get scenario
     *
     * @return string
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Casting
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
     * Set datesTournageDebut
     *
     * @param \DateTime $datesTournageDebut
     *
     * @return Casting
     */
    public function setDatesTournageDebut($datesTournageDebut)
    {
        $this->datesTournageDebut = $datesTournageDebut;

        return $this;
    }

    /**
     * Get datesTournageDebut
     *
     * @return \DateTime
     */
    public function getDatesTournageDebut()
    {
        return $this->datesTournageDebut;
    }

    /**
     * Set datesTournageEnd
     *
     * @param \DateTime $datesTournageEnd
     *
     * @return Casting
     */
    public function setDatesTournageEnd($datesTournageEnd)
    {
        $this->datesTournageEnd = $datesTournageEnd;

        return $this;
    }

    /**
     * Get datesTournageEnd
     *
     * @return \DateTime
     */
    public function getDatesTournageEnd()
    {
        return $this->datesTournageEnd;
    }

    /**
     * Set lieuTournage
     *
     * @param string $lieuTournage
     *
     * @return Casting
     */
    public function setLieuTournage($lieuTournage)
    {
        $this->lieuTournage = $lieuTournage;

        return $this;
    }

    /**
     * Get lieuTournage
     *
     * @return string
     */
    public function getLieuTournage()
    {
        return $this->lieuTournage;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Casting
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set tarif
     *
     * @param string $tarif
     *
     * @return Casting
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return string
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set member
     *
     * @param \UserBundle\Entity\User $member
     *
     * @return Casting
     */
    public function setMember(\UserBundle\Entity\User $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \UserBundle\Entity\User
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Add videoCasting
     *
     * @param \CastingBundle\Entity\VideoCasting $videoCasting
     *
     * @return Casting
     */
    public function addVideoCasting(\CastingBundle\Entity\VideoCasting $videoCasting)
    {
        $this->videoCasting[] = $videoCasting;

        return $this;
    }

    /**
     * Remove videoCasting
     *
     * @param \CastingBundle\Entity\VideoCasting $videoCasting
     */
    public function removeVideoCasting(\CastingBundle\Entity\VideoCasting $videoCasting)
    {
        $this->videoCasting->removeElement($videoCasting);
    }

    /**
     * Get videoCasting
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideoCasting()
    {
        return $this->videoCasting;
    }

    /**
     * Add castingReponse
     *
     * @param \CastingBundle\Entity\CastingReponse $castingReponse
     *
     * @return Casting
     */
    public function addCastingReponse(\CastingBundle\Entity\CastingReponse $castingReponse)
    {
        $this->castingReponse[] = $castingReponse;

        return $this;
    }

    /**
     * Remove castingReponse
     *
     * @param \CastingBundle\Entity\CastingReponse $castingReponse
     */
    public function removeCastingReponse(\CastingBundle\Entity\CastingReponse $castingReponse)
    {
        $this->castingReponse->removeElement($castingReponse);
    }

    /**
     * Get castingReponse
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCastingReponse()
    {
        return $this->castingReponse;
    }

    /**
     * Set pathPdf
     *
     * @param string $pathPdf
     *
     * @return Casting
     */
    public function setPathPdf($pathPdf)
    {
        $this->pathPdf = $pathPdf;

        return $this;
    }

    /**
     * Get pathPdf
     *
     * @return string
     */
    public function getPathPdf()
    {
        return $this->pathPdf;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Casting
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
