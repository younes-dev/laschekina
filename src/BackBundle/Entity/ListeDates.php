<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListeDates
 *
 * @ORM\Table(name="liste_dates")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\ListeDatesRepository")
 */
class ListeDates
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
     * @ORM\Column(name="startDate", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="start_time" , type="string", length=255, nullable=true)
     */
    private $starttime;

  
    /**
     * @var string
     *
     * @ORM\Column(name="end_time" , type="string", length=255, nullable=true)
     */
    private $endtime;

    /**
     * @ORM\ManyToOne(targetEntity="Medias", inversedBy="listedates"  )
     * @ORM\JoinColumn(name="id_media", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $media;
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return ListeDates
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return ListeDates
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    
    /**
     * Set endtime
     *
     * @param string $endtime
     *
     * @return ListeDates
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * Get endtime
     *
     * @return string
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set starttime
     *
     * @param string $starttime
     *
     * @return ListeDates
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;

        return $this;
    }

    /**
     * Get starttime
     *
     * @return string
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set media
     *
     * @param \BackBundle\Entity\Medias $media
     *
     * @return ListeDates
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
}
