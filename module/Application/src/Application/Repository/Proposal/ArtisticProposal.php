<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 17/08/2018
 * Time: 15:07
 */

namespace Application\Repository\Proposal;

use Util\Repository\AbstractRepository;

class ArtisticProposal extends AbstractRepository
{
    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $qb = $this->createQueryBuilder('p');

        if(!empty($criteria['artist_name'])) {
            $qb
                ->andWhere('p.artistName like :artist_name')
                ->setParameter('artist_name', '%'.$criteria['artist_name'].'%');
        }


        if(!empty($criteria['show_name'])) {
            $qb
                ->andWhere('p.showName like :show_name')
                ->setParameter('show_name', '%'.$criteria['show_name'].'%');
        }

        if(!empty($criteria['category'])) {
            $qb
                ->innerJoin('p.category', 'c')
                ->andWhere('c.id = :category')
                ->setParameter('category', $criteria['category']);
        }

        if(!empty($criteria['startDate'])) {
            $startDate = \DateTime::createFromFormat('d/m/Y', $criteria['startDate']);
            $startDate->setTime(0, 0, 0);
            $qb
                ->andWhere('p.createdAt >= :startDate')
                ->setParameter('startDate', $startDate);
        }

        if(!empty($criteria['endDate'])) {
            $endDate = \DateTime::createFromFormat('d/m/Y', $criteria['endDate']);
            $endDate->setTime(0, 0, 0);
            $qb
                ->andWhere('p.createdAt <= :endDate')
                ->setParameter('endDate', $endDate);
        }

        if(!empty($criteria['author'])) {
            $qb
                ->innerJoin('p.author', 'a')
                ->andWhere('a.name like :authorName')
                ->setParameter('authorName', '%'.$criteria['author'].'%');
        }


        return $qb;
    }

}