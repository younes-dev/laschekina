<?php

namespace Corona\EndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response
 *
 * @ORM\Table(name="response")
 * @ORM\Entity(repositoryClass="Corona\EndBundle\Repository\ResponseRepository")
 */
class Response
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="nbrKg", type="string", length=255, nullable=true)
     */
    private $nbrKg;

    /**
     * @ORM\ManyToOne(targetEntity="Questions", inversedBy="response"  )
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $question;

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
     * Set title
     *
     * @param string $title
     *
     * @return Response
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set nbrKg
     *
     * @param string $nbrKg
     *
     * @return Response
     */
    public function setNbrKg($nbrKg)
    {
        $this->nbrKg = $nbrKg;

        return $this;
    }

    /**
     * Get nbrKg
     *
     * @return string
     */
    public function getNbrKg()
    {
        return $this->nbrKg;
    }

    /**
     * Set question
     *
     * @param \Corona\EndBundle\Entity\Questions $question
     *
     * @return Response
     */
    public function setQuestion(\Corona\EndBundle\Entity\Questions $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \Corona\EndBundle\Entity\Questions
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
