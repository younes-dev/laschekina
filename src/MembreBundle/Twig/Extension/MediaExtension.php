<?php

/**
 * Created by PhpStorm.
 * User: Mouadh
 * Date: 11/06/2017
 * Time: 03:19
 */

namespace MembreBundle\Twig\Extension;

use Doctrine\ORM\EntityManagerInterface;

class MediaExtension extends \Twig_Extension
{
    protected $em;

    public function __construct (EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    public function getFilters ()
    {
        return array(
            new \Twig_SimpleFilter( 'getMedias' , array( $this , 'getMedias' ) ) ,
            new \Twig_SimpleFilter( 'getMediasNews' , array( $this , 'getMediasNews' ) ) ,
            new \Twig_SimpleFilter( 'getMediasphotos' , array( $this , 'getMediasphotos' ) ) ,
            new \Twig_SimpleFilter( 'countNbrFans' , array( $this , 'countNbrFans' ) ) ,
            new \Twig_SimpleFilter( 'countNbrLikePersonnage' , array( $this , 'countNbrLikePersonnage' ) ) ,
            new \Twig_SimpleFilter( 'countNbrDislikePersonnage' , array( $this , 'countNbrDislikePersonnage' ) ) ,
            new \Twig_SimpleFilter( 'nbrEtoile' , array( $this , 'nbrEtoile' ) ) ,

        );
    }

    function nbrEtoile ( $nbrEtoile )
    {

        $numbreEtoile = 0 ;
        switch ($nbrEtoile) {
            case ($nbrEtoile > 0):
                $numbreEtoile = 1 ;
                break;
            case ($nbrEtoile > 10000):
                $numbreEtoile = 2 ;
                break;
            case ($nbrEtoile > 100000):
                $numbreEtoile = 3 ;
                break;
            case ($nbrEtoile > 1000000):
                $numbreEtoile = 4 ;
                break;

        }


        return $numbreEtoile;
    }


 function countNbrLikePersonnage ( $IdPersonnage )
    {
        $nbrLike = $this->em->getRepository ( 'BackBundle:PersonnageVote' )->findBy(array("personnageMedia" => $IdPersonnage ,"vote" => 1));

        $nbr = 0 ;
        if($nbrLike != null)
            $nbr = count($nbrLike);

        return $nbr;
    }


    function countNbrDislikePersonnage ( $IdPersonnage )
    {
        $nbrLike = $this->em->getRepository ( 'BackBundle:PersonnageVote' )->findBy(array("personnageMedia" => $IdPersonnage ,"vote" => 0));

        $nbr = 0 ;
        if($nbrLike != null)
            $nbr = count($nbrLike);

        return $nbr;
    }


    function getMedias ( $IdMembre , $typeMedia)
    {
        $ListVip = $this->em->getRepository ( 'BackBundle:Box' )->getVipBox (   $IdMembre  );
        $AllMedias = $this->em->getRepository ( 'BackBundle:Medias' )->getMediasList (  $typeMedia , $ListVip  );

        return $AllMedias;


    }

    function countNbrFans ( $IdVip)
    {
        $ListFans = $this->em->getRepository ( 'BackBundle:Box' )->getfansBox ( $IdVip );
    
        $nbrFans = 0 ;
        if ($ListFans != null )
        $nbrFans = count($ListFans);
        return $nbrFans;


    }
   function getMediasNews ( $IdMembre , $typeMedia)
    {
        $AllMedias = $this->em->getRepository ( 'BackBundle:Collectionmedia' )->getCollectionneMedia (  $typeMedia , $IdMembre  );

        return $AllMedias;


    }

   function getMediasphotos ( $IdMembre)
    {
        $photos = $this->em->getRepository('BackBundle:Collectionmedia')->getCollectionneMediafix(4, 'photos', $IdMembre);

        return $photos;


    }

    public function getName ()
    {
        return 'medias_extension';
    }
}