<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookImagesBD
 *
 * @ORM\Table(name="book_images_b_d")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\BookImagesBDRepository")
 */
class BookImagesBD
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
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrShow", type="integer", nullable=true)
     */
    private $nbrShow;


    /**
     * @var boolean
     *
     * @ORM\Column(name="show_page_home", type="boolean", nullable=true)
     */
    private $showPageHome;

    /**
     * @ORM\ManyToOne(targetEntity="Collectionmedia", inversedBy="bookImages" )
     * @ORM\JoinColumn(name="id_book", referencedColumnName="id",nullable=true ,onDelete="CASCADE")
     */
    protected $book;


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
     * Set path
     *
     * @param string $path
     *
     * @return BookImagesBD
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set nbrShow
     *
     * @param integer $nbrShow
     *
     * @return BookImagesBD
     */
    public function setNbrShow($nbrShow)
    {
        $this->nbrShow = $nbrShow;

        return $this;
    }

    /**
     * Get nbrShow
     *
     * @return int
     */
    public function getNbrShow()
    {
        return $this->nbrShow;
    }


    /**
     * Set showPageHome
     *
     * @param integer $showPageHome
     *
     * @return BookImagesBD
     */
    public function setShowPageHome($showPageHome)
    {
        $this->showPageHome = $showPageHome;

        return $this;
    }

    /**
     * Get showPageHome
     *
     * @return int
     */
    public function getShowPageHome()
    {
        return $this->showPageHome;
    }

    /**
     * Set book
     *
     * @param \BackBundle\Entity\Collectionmedia $book
     *
     * @return BookImagesBD
     */
    public function setBook(\BackBundle\Entity\Collectionmedia $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \BackBundle\Entity\Collectionmedia
     */
    public function getBook()
    {
        return $this->book;
    }
}
