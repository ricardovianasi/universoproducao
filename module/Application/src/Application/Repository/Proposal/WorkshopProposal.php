<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 17/08/2018
 * Time: 15:07
 */

namespace Application\Repository\Proposal;


use Util\Repository\AbstractRepository;

class WorkshopProposal extends AbstractRepository
{

    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $qb = $this->createQueryBuilder('p');

        if(!empty($criteria['name'])) {
            $qb
                ->andWhere('p.name like :name')
                ->setParameter('name', '%'.$criteria['name'].'%');
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