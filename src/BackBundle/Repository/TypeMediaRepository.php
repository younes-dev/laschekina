<?php

namespace BackBundle\Repository;

/**
 * TypeMediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TypeMediaRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTousMediasListdate($languge)
    {
        $date  = date('Y-m-d');
        $maDate = strtotime($date."+ 15 days");
        $sql = $this->createQueryBuilder('t')
            ->select('t')
            ->innerJoin('t.medias', 'm')
            ->innerJoin('m.listedates', 'd')
            ->where("m.languge = :languge")
            ->andWhere("  d.startDate <= :startdate   and  d.endDate >= :startdate ")
         //   ->andWhere("  (d.startDate <= :startdate   and  d.endDate >= :startdate ) or (   d.startDate >= :startdate  and   :DatePlus >= d.startDate   ) ")

            ->setParameter ( 'startdate' , $date )
         //   ->setParameter ( 'DatePlus' , $maDate )
            ->setParameter('languge', $languge)
            ->orderBy('m.nbrShow', 'desc');
        return $sql->getQuery()->getResult();
    }

}