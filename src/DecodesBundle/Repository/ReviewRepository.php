<?php

namespace DecodesBundle\Repository;

/**
 * ReviewRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReviewRepository extends \Doctrine\ORM\EntityRepository
{
    public function findById($produitid)
    {
        $query = $this->getEntityManager()->getRepository('DecodesBundle:Review')
            ->createQueryBuilder('a')
            ->select('c.firstname, c.lastname, a.description,a.review')
            ->join('a.produit', 'e')
            ->join('a.client', 'c')
            ->where('e.id=:id')->setParameter('id',$produitid)->getQuery()->getResult();
        return $query;
    }
    public function  reviewsCount($produitid)
    {
        $query = $this->getEntityManager()->getRepository('DecodesBundle:Review')
            ->createQueryBuilder('a')
            ->select('COUNT(e) countreview')
            ->join('a.produit', 'e')
            ->where('e.id=:id')->setParameter('id',$produitid)->getQuery()->getResult();
        return $query;
    }
    public function findByUser($user,$product){
        $query = $this->getEntityManager()->getRepository('DecodesBundle:Review')
            ->createQueryBuilder('a')
            ->select('a.id id')
            ->join('a.client', 'c')
            ->join('a.produit','p')
            ->where('a.client=:us')->setParameter('us',$user)->andWhere('a.produit=:pro')->setParameter('pro',$product)->getQuery()->getResult();
        return $query;
    }
}
