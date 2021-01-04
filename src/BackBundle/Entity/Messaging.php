<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Messaging
 *
 * @ORM\Table(name="messaging")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\MessagingRepository")
 */
class Messaging
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
     * @ORM\Column(name="dateofsending", type="datetime")
     */
    private $dateofsending;

    /**
     * @var boolean
     * @ORM\Column(name="delete_enable" ,type="boolean")
     */
    private  $deleteenable ;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    /**
     * @var boolean
     * @ORM\Column(name="enable" ,type="boolean")
     */
    private $enable;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="messagesent" )
     * @ORM\JoinColumn(name="user_emitter", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $useremitter;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="messagereceived" )
     * @ORM\JoinColumn(name="user_receiver", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $userreceiver;


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
     * Set dateofsending
     *
     * @param \DateTime $dateofsending
     *
     * @return Messaging
     */
    public function setDateofsending($dateofsending)
    {
        $this->dateofsending = $dateofsending;

        return $this;
    }

    /**
     * Get dateofsending
     *
     * @return \DateTime
     */
    public function getDateofsending()
    {
        return $this->dateofsending;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Messaging
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public  function  __construct ()
    {
        $this->setDateofsending(new \DateTime());
        $this->setEnable(0);
        $this->setDeleteenable(0);

    }


    /**
     * Set useremitter
     *
     * @param \UserBundle\Entity\User $useremitter
     *
     * @return Messaging
     */
    public function setUseremitter(\UserBundle\Entity\User $useremitter = null)
    {
        $this->useremitter = $useremitter;

        return $this;
    }

    /**
     * Get useremitter
     *
     * @return \UserBundle\Entity\User
     */
    public function getUseremitter()
    {
        return $this->useremitter;
    }

    /**
     * Set userreceiver
     *
     * @param \UserBundle\Entity\User $userreceiver
     *
     * @return Messaging
     */
    public function setUserreceiver(\UserBundle\Entity\User $userreceiver = null)
    {
        $this->userreceiver = $userreceiver;

        return $this;
    }

    /**
     * Get userreceiver
     *
     * @return \UserBundle\Entity\User
     */
    public function getUserreceiver()
    {
        return $this->userreceiver;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return Messaging
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
     * Set deleteenable
     *
     * @param boolean $deleteenable
     *
     * @return Messaging
     */
    public function setDeleteenable($deleteenable)
    {
        $this->deleteenable = $deleteenable;

        return $this;
    }

    /**
     * Get deleteenable
     *
     * @return boolean
     */
    public function getDeleteenable()
    {
        return $this->deleteenable;
    }
}
