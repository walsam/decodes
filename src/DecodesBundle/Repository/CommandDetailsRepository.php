<?php

namespace DecodesBundle\Repository;

/**
 * CommandDetailsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandDetailsRepository extends \Doctrine\ORM\EntityRepository
{
    public function bestseller()
    {
        $query = $this->getEntityManager()->getRepository('DecodesBundle:CommandDetails')
            ->createQueryBuilder('a')
            ->select('e.id,e.name,e.picture,e.promoprice,e.price,COUNT(e) countbest')
            ->join('a.produit','e')->groupBy('e')->orderBy('countbest','DESC')->setMaxResults(8)->getQuery()->getResult();

        return $query;
    }
    public function shopinglistcount($user)
    {
        $query = $this->getEntityManager()->getRepository('DecodesBundle:CommandDetails')
            ->createQueryBuilder('a')
            ->select('COUNT(e) countlist')
            ->join('a.command','e')->where('e.client=:user')->setParameter('user', $user)->andWhere('e.confirmation=0')->getQuery()->getResult();

        return $query;
    }

}
