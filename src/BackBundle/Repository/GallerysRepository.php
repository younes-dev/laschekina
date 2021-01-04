<?php

namespace BackBundle\Repository;

/**
 * GallerysRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GallerysRepository extends \Doctrine\ORM\EntityRepository
{
    public function getGalleryList($type_gallery)
    {
        $sql = $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('c.typegallery', 't')
            ->where("t.title = :type")
            ->setParameter('type', $type_gallery);

        return $sql->getQuery()->getResult();

    }
  public function getGalleryMediaList($type_gallery , $idMedia)
    {
        $sql = $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('c.typegallery', 't')
            ->innerJoin('c.media', 'm')
            ->where("t.title = :type")
            ->andWhere ("m.id = :idMedia")
            ->setParameter('type', $type_gallery)
            ->setParameter('idMedia', $idMedia);

        return $sql->getQuery()->getResult();

    }

    public function getGalleryListshow($type_gallery)
    {
        $sql = $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('c.typegallery', 't')
            ->where("t.title = :type")
             ->andWhere("c.state = 1 ")
            ->setParameter('type', $type_gallery);

        return $sql->getQuery()->getResult();

    }
    public function getRandomEntities($count , $type_gallery)
    {
        return  $this->createQueryBuilder('c')
            ->addSelect('RAND() as HIDDEN rand')
            ->innerJoin('c.typegallery', 't')
            ->where("t.title = :type")
            ->andWhere("c.state = 1 ")
            ->setParameter('type', $type_gallery)
            ->addOrderBy('rand')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }
   public function getRandomSingleEntity( $type_gallery)
    {
        return  $this->createQueryBuilder('c')
            ->addSelect('RAND() as HIDDEN rand')
            ->innerJoin('c.typegallery', 't')
            ->where("t.title = :type")
            ->andWhere("c.state = 1 ")
            ->setParameter('type', $type_gallery)
            ->addOrderBy('rand')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function getRandomMedia($count , $type_gallery , $idMedia)
    {
        return  $this->createQueryBuilder('c')
            ->addSelect('RAND() as HIDDEN rand')
            ->innerJoin('c.typegallery', 't')
            ->innerJoin('c.media', 'm')
            ->where("t.title = :type")
            ->andWhere("c.state = 1 ")
            ->andWhere ("m.id = :idMedia")
            ->setParameter('type', $type_gallery)
            ->setParameter('idMedia', $idMedia)
            ->addOrderBy('rand')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

}