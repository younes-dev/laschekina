<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Humeur
 *
 * @ORM\Table(name="humeur")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\HumeurRepository")
 */
class Humeur
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
     * @ORM\Column(name="picture", type="string",nullable = true)
     * @Assert\Image(
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/gif",
     *      }
     *     )
     */
    private $picture;


    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="humeur" , fetch="LAZY")
     * @ORM\JoinColumn(name="member", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $member;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;


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
     * Set type
     *
     * @param string $type
     *
     * @return Humeur
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Humeur
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set member
     *
     * @param \UserBundle\Entity\User $member
     *
     * @return Humeur
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
