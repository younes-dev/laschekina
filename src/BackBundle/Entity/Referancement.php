<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Referancement
 *
 * @ORM\Table(name="referancement")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\ReferancementRepository")
 */
class Referancement
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type_page", type="string", length=255, nullable=true)
     */
    private $typePage;


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
     * Set description
     *
     * @param string $description
     *
     * @return Referancement
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set typePage
     *
     * @param string $typePage
     *
     * @return Referancement
     */
    public function setTypePage($typePage)
    {
        $this->typePage = $typePage;

        return $this;
    }

    /**
     * Get typePage
     *
     * @return string
     */
    public function getTypePage()
    {
        return $this->typePage;
    }
}
