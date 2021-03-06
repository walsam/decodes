<?php

namespace DecodesBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByCategory($category)
    {
        $query = $this->getEntityManager()->getRepository('DecodesBundle:Product')
            ->createQueryBuilder('a')
            ->select('a.promoprice,a.price,a.picture,a.name,a.description,a.id')
            ->join('a.categorie','e')->where('e=:category')->setParameter('category',$category)->getQuery()->getResult();

        return $query;
    }
}
