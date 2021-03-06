<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\ActivityRepository")
 */
class Activity
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
     * @ORM\Column(name="activity_fr", type="string", length=255)
     */
    private $activityfr;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_en", type="string", length=255)
     */
    private $activityen;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_es", type="string", length=255)
     */
    private $activityes;

    /**
     * @ORM\ManyToMany(targetEntity="Personnage", cascade={"persist"}, fetch="LAZY" , mappedBy="activity" )
     */
    protected $personnage;

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
     * Set activityfr
     *
     * @param string $activityfr
     *
     * @return Activity
     */
    public function setActivityfr($activityfr)
    {
        $this->activityfr = $activityfr;

        return $this;
    }

    /**
     * Get activityfr
     *
     * @return string
     */
    public function getActivityfr()
    {
        return $this->activityfr;
    }

    /**
     * Set activityen
     *
     * @param string $activityen
     *
     * @return Activity
     */
    public function setActivityen($activityen)
    {
        $this->activityen = $activityen;

        return $this;
    }

    /**
     * Get activityen
     *
     * @return string
     */
    public function getActivityen()
    {
        return $this->activityen;
    }

    /**
     * Set activityes
     *
     * @param string $activityes
     *
     * @return Activity
     */
    public function setActivityes($activityes)
    {
        $this->activityes = $activityes;

        return $this;
    }

    /**
     * Get activityes
     *
     * @return string
     */
    public function getActivityes()
    {
        return $this->activityes;
    }

    public function __toString ()
    {
        return $this->activityfr ;
    }

    /**
     * Add personnage
     *
     * @param \BackBundle\Entity\Personnage $personnage
     *
     * @return Activity
     */
    public function addPersonnage(\BackBundle\Entity\Personnage $personnage)
    {
        $this->personnage[] = $personnage;

        return $this;
    }

    /**
     * Remove personnage
     *
     * @param \BackBundle\Entity\Personnage $personnage
     */
    public function removePersonnage(\BackBundle\Entity\Personnage $personnage)
    {
        $this->personnage->removeElement($personnage);
    }

    /**
     * Get personnage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonnage()
    {
        return $this->personnage;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personnage = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
