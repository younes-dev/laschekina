<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="category_fr", type="string", length=255)
     */
    private $categoryfr;

    /**
     * @var string
     *
     * @ORM\Column(name="category_en", type="string", length=255)
     */
    private $categoryen;

    /**
     * @var string
     *
     * @ORM\Column(name="category_es", type="string", length=255)
     */
    private $categoryes;

    /**
     * @ORM\ManyToMany(targetEntity="Personnage", cascade={"persist"}, fetch="LAZY" , mappedBy="category" )
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
     * Set categoryfr
     *
     * @param string $categoryfr
     *
     * @return Category
     */
    public function setCategoryfr($categoryfr)
    {
        $this->categoryfr = $categoryfr;

        return $this;
    }

    /**
     * Get categoryfr
     *
     * @return string
     */
    public function getCategoryfr()
    {
        return $this->categoryfr;
    }

    /**
     * Set categoryen
     *
     * @param string $categoryen
     *
     * @return Category
     */
    public function setCategoryen($categoryen)
    {
        $this->categoryen = $categoryen;

        return $this;
    }

    /**
     * Get categoryen
     *
     * @return string
     */
    public function getCategoryen()
    {
        return $this->categoryen;
    }

    /**
     * Set categoryes
     *
     * @param string $categoryes
     *
     * @return Category
     */
    public function setCategoryes($categoryes)
    {
        $this->categoryes = $categoryes;

        return $this;
    }

    /**
     * Get categoryes
     *
     * @return string
     */
    public function getCategoryes()
    {
        return $this->categoryes;
    }

    public function __toString ()
    {
        return $this->categoryfr ;
    }


    /**
     * Add personnage
     *
     * @param \BackBundle\Entity\Personnage $personnage
     *
     * @return Category
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
