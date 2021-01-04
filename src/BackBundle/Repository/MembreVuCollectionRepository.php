<?php

namespace BackBundle\Repository;

/**
 * MembreVuCollectionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MembreVuCollectionRepository extends \Doctrine\ORM\EntityRepository
{

    public function MembreVuCollectionAlert (  $userCourant     )
    {
        $etat = 0 ;
        $sql = $this->createQueryBuilder ( 'a' )
            ->where("a.memberCollection = :userCourant  " )
            ->andWhere("a.collectionNews = :etat or a.collectionPhoto = :etat  or a.collectionMedia = :etat " )
            ->setParameter ( 'userCourant' , $userCourant )
            ->setParameter ( 'etat' , $etat )
        ;
        return $sql->getQuery ()->getResult();
    }


    public function MembreVuCollectionNews (  $userCourant ,  $membre     )
    {
        $etat = 0 ;
        $sql = $this->createQueryBuilder ( 'a' )
            ->where ( "a.memberEmitter =  :userMembre " )
            ->andWhere("a.memberCollection = :userCourant  " )
            ->andWhere("a.collectionNews = :etat" )
            ->setParameter ( 'userCourant' , $userCourant )
            ->setParameter ( 'userMembre' , $membre )
            ->setParameter ( 'etat' , $etat )
        ;
        return $sql->getQuery ()->getResult();
    }


    public function MembreVuCollectionPhoto (  $userCourant ,  $membre   )
    {  $etat = 0 ;
        $sql = $this->createQueryBuilder ( 'a' )
            ->where ( "a.memberEmitter =  :userMembre " )
            ->andWhere("a.memberCollection = :userCourant  " )
            ->andWhere("a.collectionPhoto = :etat" )
            ->setParameter ( 'userCourant' , $userCourant )
            ->setParameter ( 'userMembre' , $membre )
            ->setParameter ( 'etat' , $etat )
        ;
        return $sql->getQuery ()->getResult();
    }


    public function MembreVuCollectionMedia (  $userCourant ,  $membre   )
    {  $etat = 0 ;
        $sql = $this->createQueryBuilder ( 'a' )
            ->where ( "a.memberEmitter =  :userMembre " )
            ->andWhere("a.memberCollection = :userCourant  " )
            ->andWhere("a.collectionMedia = :etat" )
            ->setParameter ( 'userCourant' , $userCourant )
            ->setParameter ( 'userMembre' , $membre )
            ->setParameter ( 'etat' , $etat )
        ;
        return $sql->getQuery ()->getResult();
    }


    public function findListeCollection ( $membre , $userCourant  )
    {
        $sql = $this->createQueryBuilder ( 'a' )
            ->where ( "a.memberEmitter = :userCourant or a.memberCollection = :userCourant " )
            ->andWhere("a.memberEmitter = :userMembre or a.memberCollection = :userMembre " )
            ->setParameter ( 'userCourant' , $userCourant )
            ->setParameter ( 'userMembre' , $membre )
        ;
        return $sql->getQuery ()->getResult();
    }

    public function findListeCollectionMembre ( $membre  )
    {
        $sql = $this->createQueryBuilder ( 'a' )
            ->where ( "a.memberEmitter = :userMembre or a.memberCollection = :userMembre " )


            ->setParameter ( 'userMembre' , $membre )
        ;
        return $sql->getQuery ()->getResult();
    }

}
