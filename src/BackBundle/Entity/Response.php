<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response
 *
 * @ORM\Table(name="response_questions_medias")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\ResponseRepository")
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
     * @var int
     *
     * @ORM\Column(name="oui", type="integer")
     */
    private $oui;

    /**
     * @var string
     *
     * @ORM\Column(name="non", type="string", length=255)
     */
    private $non;

    /**
     * @ORM\ManyToOne(targetEntity="Questions", inversedBy="response")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

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
     * Set oui
     *
     * @param integer $oui
     *
     * @return Response
     */
    public function setOui($oui)
    {
        $this->oui = $oui;

        return $this;
    }

    /**
     * Get oui
     *
     * @return int
     */
    public function getOui()
    {
        return $this->oui;
    }

    /**
     * Set non
     *
     * @param string $non
     *
     * @return Response
     */
    public function setNon($non)
    {
        $this->non = $non;

        return $this;
    }

    /**
     * Get non
     *
     * @return string
     */
    public function getNon()
    {
        return $this->non;
    }

    /**
     * Set question
     *
     * @param \BackBundle\Entity\Questions $question
     *
     * @return Response
     */
    public function setQuestion(\BackBundle\Entity\Questions $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \BackBundle\Entity\Questions
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
