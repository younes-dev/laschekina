<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payments")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\PaymentRepository")
 */
class Payments
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
     * @var string
     *
     * @ORM\Column(name="id_payment", type="string", length=255, unique=true)
     */
    private $idPayment;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="payment" )
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $user;

   /**
     * @ORM\ManyToOne(targetEntity="Collectionmedia", inversedBy="payment" )
     * @ORM\JoinColumn(name="id_book", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $book;

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
     *  Construct 
     */
    public function  __construct (){
        $this->setDateCreate(new \DateTime());
    }

   

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Payment
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
     * Set idPayment
     *
     * @param string $idPayment
     *
     * @return Payment
     */
    public function setIdPayment($idPayment)
    {
        $this->idPayment = $idPayment;

        return $this;
    }

    /**
     * Get idPayment
     *
     * @return string
     */
    public function getIdPayment()
    {
        return $this->idPayment;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Payment
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set book
     *
     * @param \BackBundle\Entity\Collectionmedia $book
     *
     * @return Payment
     */
    public function setBook(\BackBundle\Entity\Collectionmedia $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \BackBundle\Entity\Collectionmedia
     */
    public function getBook()
    {
        return $this->book;
    }
}
