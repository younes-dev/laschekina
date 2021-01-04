<?php

namespace Corona\EndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NbrCorona
 *
 * @ORM\Table(name="nbr_corona")
 * @ORM\Entity(repositoryClass="Corona\EndBundle\Repository\NbrCoronaRepository")
 */
class NbrCorona
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
     * @ORM\Column(name="nbr", type="integer")
     */
    private $nbr;


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
     * Set nbr
     *
     * @param integer $nbr
     *
     * @return NbrCorona
     */
    public function setNbr($nbr)
    {
        $this->nbr = $nbr;

        return $this;
    }

    /**
     * Get nbr
     *
     * @return int
     */
    public function getNbr()
    {
        return $this->nbr;
    }
}

