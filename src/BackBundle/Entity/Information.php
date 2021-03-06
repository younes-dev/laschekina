<?php

namespace BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Information
 *
 * @ORM\Table(name="information")
 * @ORM\Entity(repositoryClass="BackBundle\Repository\InformationRepository")
 */
class Information
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
     * @ORM\Column(name="adress", type="string", length=255, nullable=true)
     */
    private $adress;


    /**
     * @var float
     *
     * @ORM\Column(name="tax", type="float", length=255, nullable=true)
     */
    private $tax;


    /**
     * @var float
     *
     * @ORM\Column(name="delivery", type="float", length=255, nullable=true)
     */
    private $delivery;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="background_picture", type="string", length=255, nullable=true)
     * @Assert\Image(
     *     minWidth  = 1920,
     *     minHeight = 600,
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          }
     *     )
     */
    private $backgroundpicture;


    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="google_plus", type="string", length=255, nullable=true)
     */
    private $googleplus;

     /**
     * @var string
     *
     * @ORM\Column(name="linkedin", type="string", length=255, nullable=true)
     */
    private $linkedin;

    /**
     * @var string
     *
     * @ORM\Column(name="description_fr", type="text", nullable=true)
     */
    private $descriptionfr;

    /**
     * @var string
     *
     * @ORM\Column(name="description_pub", type="text", nullable=true)
     */
    private $descriptionPub;

    /**
     * @var string
     *
     * @ORM\Column(name="description_en", type="text", nullable=true)
     */
    private $descriptionen;

    /**
     * @var string
     *
     * @ORM\Column(name="description_es", type="text", nullable=true)
     */
    private $descriptiones;

  
    /**
     * @var string
     *
     * @ORM\Column(name="description_contact_fr", type="text", nullable=true)
     */
    private $descriptionContactfr;

    /**
     * @var string
     *
     * @ORM\Column(name="description_contact_en", type="text", nullable=true)
     */
    private $descriptionContacten;

    /**
     * @var string
     *
     * @ORM\Column(name="description_contact_es", type="text", nullable=true)
     */
    private $descriptionContactes;

   /**
     * @var string
     *
     * @ORM\Column(name="description_popup_fr", type="text", nullable=true)
     */
    private $descriptionpopupfr;

    /**
     * @var string
     *
     * @ORM\Column(name="description_popup_en", type="text", nullable=true)
     */
    private $descriptionpopupen;

    /**
     * @var string
     *
     * @ORM\Column(name="description_popup_es", type="text", nullable=true)
     */
    private $descriptionpopupes;


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
     * Set adress
     *
     * @param string $adress
     *
     * @return Information
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Information
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Information
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Information
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return Information
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set googleplus
     *
     * @param string $googleplus
     *
     * @return Information
     */
    public function setGoogleplus($googleplus)
    {
        $this->googleplus = $googleplus;

        return $this;
    }

    /**
     * Get googleplus
     *
     * @return string
     */
    public function getGoogleplus()
    {
        return $this->googleplus;
    }

    /**
     * Set descriptionfr
     *
     * @param string $descriptionfr
     *
     * @return Information
     */
    public function setDescriptionfr($descriptionfr)
    {
        $this->descriptionfr = $descriptionfr;

        return $this;
    }

    /**
     * Get descriptionfr
     *
     * @return string
     */
    public function getDescriptionfr()
    {
        return $this->descriptionfr;
    }

    /**
     * Set descriptionen
     *
     * @param string $descriptionen
     *
     * @return Information
     */
    public function setDescriptionen($descriptionen)
    {
        $this->descriptionen = $descriptionen;

        return $this;
    }

    /**
     * Get descriptionen
     *
     * @return string
     */
    public function getDescriptionen()
    {
        return $this->descriptionen;
    }

    /**
     * Set descriptiones
     *
     * @param string $descriptiones
     *
     * @return Information
     */
    public function setDescriptiones($descriptiones)
    {
        $this->descriptiones = $descriptiones;

        return $this;
    }

    /**
     * Get descriptiones
     *
     * @return string
     */
    public function getDescriptiones()
    {
        return $this->descriptiones;
    }

   


    /**
     * Set descriptionpopupfr
     *
     * @param string $descriptionpopupfr
     *
     * @return Information
     */
    public function setDescriptionpopupfr($descriptionpopupfr)
    {
        $this->descriptionpopupfr = $descriptionpopupfr;

        return $this;
    }

    /**
     * Get descriptionpopupfr
     *
     * @return string
     */
    public function getDescriptionpopupfr()
    {
        return $this->descriptionpopupfr;
    }

    /**
     * Set descriptionpopupen
     *
     * @param string $descriptionpopupen
     *
     * @return Information
     */
    public function setDescriptionpopupen($descriptionpopupen)
    {
        $this->descriptionpopupen = $descriptionpopupen;

        return $this;
    }

    /**
     * Get descriptionpopupen
     *
     * @return string
     */
    public function getDescriptionpopupen()
    {
        return $this->descriptionpopupen;
    }

    /**
     * Set descriptionpopupes
     *
     * @param string $descriptionpopupes
     *
     * @return Information
     */
    public function setDescriptionpopupes($descriptionpopupes)
    {
        $this->descriptionpopupes = $descriptionpopupes;

        return $this;
    }

    /**
     * Get descriptionpopupes
     *
     * @return string
     */
    public function getDescriptionpopupes()
    {
        return $this->descriptionpopupes;
    }

    /**
     * Set tax
     *
     * @param float $tax
     *
     * @return Information
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set delivery
     *
     * @param float $delivery
     *
     * @return Information
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return float
     */
    public function getDelivery()
    {
        return $this->delivery;
    }



     /**
     * Set descriptionContactfr
     *
     * @param string $descriptionContactfr
     *
     * @return Information
     */
    public function setDescriptionContactfr($descriptionContactfr)
    {
        $this->descriptionContactfr = $descriptionContactfr;

        return $this;
    }

    /**
     * Get descriptionContactfr
     *
     * @return string
     */
    public function getDescriptionContactfr()
    {
        return $this->descriptionContactfr;
    }

    /**
    * Set descriptionContacten
    *
    * @param string $descriptionContacten
    *
    * @return Information
    */
   public function setDescriptionContacten($descriptionContacten)
   {
       $this->descriptionContacten = $descriptionContacten;

       return $this;
   }

   /**
    * Get descriptionContacten
    *
    * @return string
    */
   public function getDescriptionContacten()
   {
       return $this->descriptionContacten;
   }


    /**
    * Set descriptionContactes
    *
    * @param string $descriptionContactes
    *
    * @return Information
    */
    public function setDescriptionContactes($descriptionContactes)
    {
        $this->descriptionContactes = $descriptionContactes;
 
        return $this;
    }
 
    /**
     * Get descriptionContactes
     *
     * @return string
     */
    public function getDescriptionContactes()
    {
        return $this->descriptionContactes;
    }
 
    /**
     * Set linkedin
     *
     * @param string $linkedin
     *
     * @return Information
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * Set backgroundpicture
     *
     * @param string $backgroundpicture
     *
     * @return Information
     */
    public function setBackgroundpicture($backgroundpicture)
    {
        $this->backgroundpicture = $backgroundpicture;

        return $this;
    }

    /**
     * Get backgroundpicture
     *
     * @return string
     */
    public function getBackgroundpicture()
    {
        return $this->backgroundpicture;
    }

    /**
     * Set descriptionPub
     *
     * @param string $descriptionPub
     *
     * @return Information
     */
    public function setDescriptionPub($descriptionPub)
    {
        $this->descriptionPub = $descriptionPub;

        return $this;
    }

    /**
     * Get descriptionPub
     *
     * @return string
     */
    public function getDescriptionPub()
    {
        return $this->descriptionPub;
    }
}
