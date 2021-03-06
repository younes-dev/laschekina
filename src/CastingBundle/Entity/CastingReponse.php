<?php

namespace CastingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CastingReponse
 *
 * @ORM\Table(name="casting_reponse")
 * @ORM\Entity(repositoryClass="CastingBundle\Repository\CastingReponseRepository")
 */
class CastingReponse
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreate", type="datetime", nullable=true)
     */
    private $dateCreate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="resultat", type="integer", nullable=true)
     */
    private $resultat;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="castingReponse"  )
     * @ORM\JoinColumn(name="id_member", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $member;

    /**
     * @ORM\ManyToOne(targetEntity="Casting", inversedBy="castingReponse"  )
     * @ORM\JoinColumn(name="id_casting", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $casting;


    /**
     * @ORM\OneToMany(targetEntity="ReponseVideoCasting", cascade={"persist"},  mappedBy="responseCasting" )
     */
    protected $reponseVideoCasting;


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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return CastingReponse
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDateCreate(new \DateTime());
        $this->reponseVideoCasting = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set member
     *
     * @param \UserBundle\Entity\User $member
     *
     * @return CastingReponse
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
     * Set casting
     *
     * @param \CastingBundle\Entity\Casting $casting
     *
     * @return CastingReponse
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
     * @return CastingReponse
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

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return CastingReponse
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
     * Set resultat
     *
     * @param integer $resultat
     *
     * @return CastingReponse
     */
    public function setResultat($resultat)
    {
        $this->resultat = $resultat;

        return $this;
    }

    /**
     * Get resultat
     *
     * @return integer
     */
    public function getResultat()
    {
        return $this->resultat;
    }
}
