<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Crossword
 *
 * @ORM\Table(name="crossword")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\CrosswordRepository")
 */
class Crossword
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
     * @ORM\Column(name="question", type="string", length=255 , nullable = true)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="type_mot", type="string", length=255 , nullable = true)
     */
    private $typemot;

    /**
     * @var string
     *
     * @ORM\Column(name="type_user", type="string", length=255 , nullable = true)
     *
     */
    private $typeuser;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="crossword" )
     * @ORM\JoinColumn(name="user_create", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $creator;


    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="string", length=255 , nullable = true)
     */
    private $reponse;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text" , nullable = true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="statue", type="boolean" , nullable = true)
     */
    private $statue;


    /**
     * Get id
     *
     * @return int
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * Set question
     *
     * @param string $question
     *
     * @return Crossword
     */
    public function setQuestion ( $question )
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion ()
    {
        return $this->question;
    }

    /**
     * Set reponse
     *
     * @param string $reponse
     *
     * @return Crossword
     */
    public function setReponse ( $reponse )
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return string
     */
    public function getReponse ()
    {
        return $this->reponse;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Crossword
     */
    public function setDescription ( $description )
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription ()
    {
        return $this->description;
    }

    /**
     * Set statue
     *
     * @param boolean $statue
     *
     * @return Crossword
     */
    public function setStatue ( $statue )
    {
        $this->statue = $statue;

        return $this;
    }

    /**
     * Get statue
     *
     * @return bool
     */
    public function getStatue ()
    {
        return $this->statue;
    }

    /**
     * Set typemot
     *
     * @param string $typemot
     *
     * @return Crossword
     */
    public function setTypemot($typemot)
    {
        $this->typemot = $typemot;

        return $this;
    }

    /**
     * Get typemot
     *
     * @return string
     */
    public function getTypemot()
    {
        return $this->typemot;
    }

    /**
     * Set typeuser
     *
     * @param string $typeuser
     *
     * @return Crossword
     */
    public function setTypeuser($typeuser)
    {
        $this->typeuser = $typeuser;

        return $this;
    }

    /**
     * Get typeuser
     *
     * @return string
     */
    public function getTypeuser()
    {
        return $this->typeuser;
    }

    /**
     * Set creator
     *
     * @param \UserBundle\Entity\User $creator
     *
     * @return Crossword
     */
    public function setCreator(\UserBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \UserBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }
   public function __construct ()
   {
       $this->setStatue(1);
   }

}
