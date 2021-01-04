<?php

namespace BackBundle\Repository;

/**
 * ActivityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonnageMediaRepository extends \Doctrine\ORM\EntityRepository
{

    public function findgallerysCarouselHomeMedia(  )

    {
        $sql = $this->createQueryBuilder('c')
            ->select('c')
         //   ->innerJoin('c.member', 'u')
           ->where("c.showPageBanniere = 0")
           ->orWhere("c.showPageBanniere is Null")
          //  ->where("c.type = :type")
           ->orderBy("c.id","DESC")
           // ->setParameter('type', $type)
         //   ->setMaxResults ( $count )
        ;
        return $sql->getQuery()->getResult();
    }

    public function getPersonnage (  $ListIdVip )
    {

          $sql = $this->createQueryBuilder ( 'p' )
            ->select ( 'p' )
            ->innerJoin('p.medias', 'm')
             ->where ( "m.id IN (:ListIdMedia)" )

            ->setParameter('ListIdMedia', $ListIdVip)
            ->orderBy ( 'm.id' , 'DESC' );
        return $sql->getQuery ()->getResult ();

    }
}