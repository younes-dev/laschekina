<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookVotes
 *
 * @ORM\Table(name="book_votes")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\BookVotesRepository")
 */
class BookVotes
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
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Collectionmedia", inversedBy="bookVotes" )
     * @ORM\JoinColumn(name="id_book", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $book;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="bookVotes" )
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $user;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BookVotes
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set book
     *
     * @param \BackBundle\Entity\Collectionmedia $book
     *
     * @return BookVotes
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

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return BookVotes
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
}
