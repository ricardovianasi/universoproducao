<?php
namespace Application\Repository\Channel;

use Doctrine\ORM\Query\Expr\Join;
use Util\Repository\AbstractRepository;

class Video extends AbstractRepository
{
    public function findVideosByCategory($catId, $limit=null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p')
            ->innerJoin('p.categories', 'c')
            ->where('c.id = :catId')
            ->setParameter('catId', $catId)
            ->orderBy('p.date', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}