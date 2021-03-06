<?php
namespace BackBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * PersonnageVote
 *
 * @ORM\Table(name="personnage_vote")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\PersonnageVoteRepository")
 */
class PersonnageVote
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
     * @var bool
     *
     * @ORM\Column(name="vote", type="boolean", nullable=true)
     */
    private $vote;
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="BackBundle\Entity\PersonnageMedia", inversedBy="personnageVotes")
     */
    private $personnageMedia;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="PersonnageVotes")
     */
    private $user;
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
     * Set vote
     *
     * @param boolean $vote
     *
     * @return PersonnageVote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
        return $this;
    }
    /**
     * Get vote
     *
     * @return bool
     */
    public function getVote()
    {
        return $this->vote;
    }
    /**
     * Set personnageMedia
     *
     * @param \BackBundle\Entity\PersonnageMedia $personnageMedia
     *
     * @return PersonnageVote
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
    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return PersonnageVote
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PersonnageVote
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return PersonnageVote
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
