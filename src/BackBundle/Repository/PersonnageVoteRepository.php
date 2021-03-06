<?php
namespace BackBundle\Repository;
/**
 * PersonnageVoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonnageVoteRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNumberLike($id)
    {
        return $this->createQueryBuilder('pv')
            ->join('pv.personnageMedia', 'pm')
            ->where('pv.vote = :vote')
            ->andWhere('pm.id = :id')
            ->select('COUNT(pv)')
            ->setParameter('vote', 1)
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    public function getNumberDislike($id)
    {
        return $this->createQueryBuilder('pv')
            ->where('pv.vote = :vote')
            ->select('COUNT(pv)')
            ->setParameter('vote', 0)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}