<?php

namespace CastingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReponseVideoCasting
 *
 * @ORM\Table(name="reponse_video_casting")
 * @ORM\Entity(repositoryClass="CastingBundle\Repository\ReponseVideoCastingRepository")
 */
class ReponseVideoCasting
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
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="reponseVideoCasting"  )
     * @ORM\JoinColumn(name="id_member", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $member;



    /**
     * @ORM\ManyToOne(targetEntity="VideoCasting", inversedBy="reponseVideoCasting"  )
     * @ORM\JoinColumn(name="id_video_casting", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $videoCasting;


    /**
     * @ORM\ManyToOne(targetEntity="CastingReponse", inversedBy="reponseVideoCasting"  )
     * @ORM\JoinColumn(name="id_response_casting", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $castingReponse;




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
     * @return ReponseVideoCasting
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
     * Set member
     *
     * @param \UserBundle\Entity\User $member
     *
     * @return ReponseVideoCasting
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
     * Set videoCasting
     *
     * @param \CastingBundle\Entity\VideoCasting $videoCasting
     *
     * @return ReponseVideoCasting
     */
    public function setVideoCasting(\CastingBundle\Entity\VideoCasting $videoCasting = null)
    {
        $this->videoCasting = $videoCasting;

        return $this;
    }

    /**
     * Get videoCasting
     *
     * @return \CastingBundle\Entity\VideoCasting
     */
    public function getVideoCasting()
    {
        return $this->videoCasting;
    }

    /**
     * Set responseCasting
     *
     * @param \CastingBundle\Entity\ResponseCasting $responseCasting
     *
     * @return ReponseVideoCasting
     */
    public function setResponseCasting(\CastingBundle\Entity\ResponseCasting $responseCasting = null)
    {
        $this->responseCasting = $responseCasting;

        return $this;
    }

    /**
     * Get responseCasting
     *
     * @return \CastingBundle\Entity\ResponseCasting
     */
    public function getResponseCasting()
    {
        return $this->responseCasting;
    }

    /**
     * Set castingReponse
     *
     * @param \CastingBundle\Entity\CastingReponse $castingReponse
     *
     * @return ReponseVideoCasting
     */
    public function setCastingReponse(\CastingBundle\Entity\CastingReponse $castingReponse = null)
    {
        $this->castingReponse = $castingReponse;

        return $this;
    }

    /**
     * Get castingReponse
     *
     * @return \CastingBundle\Entity\CastingReponse
     */
    public function getCastingReponse()
    {
        return $this->castingReponse;
    }
}
