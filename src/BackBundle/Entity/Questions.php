<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Questions
 *
 * @ORM\Table(name="questions_medias")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\QuestionsRepository")
 */
class Questions
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
     * @ORM\Column(name="question", type="string", length=255)
     */
    private $question;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;


    /**
     * @ORM\ManyToOne(targetEntity="Medias", inversedBy="questions")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    private $media;


    /**
     * @ORM\OneToMany(targetEntity="Response", cascade={"persist"} , mappedBy="questions" )
     */
    protected $response;


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
     * Set question
     *
     * @param string $question
     *
     * @return Questions
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Questions
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
        $this->response = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreate = new \DateTime();
    }

    /**
     * Set media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return Questions
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
     * Add response
     *
     * @param \BackBundle\Entity\Response $response
     *
     * @return Questions
     */
    public function addResponse(\BackBundle\Entity\Response $response)
    {
        $this->response[] = $response;

        return $this;
    }

    /**
     * Remove response
     *
     * @param \BackBundle\Entity\Response $response
     */
    public function removeResponse(\BackBundle\Entity\Response $response)
    {
        $this->response->removeElement($response);
    }

    /**
     * Get response
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponse()
    {
        return $this->response;
    }
}
