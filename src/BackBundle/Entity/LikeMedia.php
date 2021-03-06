<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LikeMedia
 *
 * @ORM\Table(name="like_media")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\LikeMediaRepository")
 */
class LikeMedia
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
     * @ORM\ManyToOne(targetEntity="Medias", inversedBy="likemedia"  )
     * @ORM\JoinColumn(name="medias", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $media;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="likemedia"  )
     * @ORM\JoinColumn(name="member", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $member;



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
     * Set media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return LikeMedia
     */
    public function setMedia(\BackBundle\Entity\Medias $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \BackBundle\Entity\Medias
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set member
     *
     * @param \UserBundle\Entity\User $member
     *
     * @return LikeMedia
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
}
