<?php
namespace Application\Repository\Movie;

use Util\Repository\AbstractRepository;

class Movie extends AbstractRepository
{
    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $qb = $this->createQueryBuilder('p');
        $qb->leftJoin('p.subscriptions', 'e');


        if(!empty($criteria['id_from'])) {
            $qb
                ->andWhere('p.id >= :id_from')
                ->setParameter('id_from', $criteria['id_from']);
        }

        if(!empty($criteria['id_to'])) {
            $qb
                ->andWhere('p.id <= :id_to')
                ->setParameter('id_to', $criteria['id_to']);
        }

        if(!empty($criteria['title'])) {
            $qb
                ->andWhere('p.title like :title')
                ->setParameter('title', '%'.$criteria['title'].'%');
        }

        if(!empty($criteria['category'])) {
        }

        if(!empty($criteria['events'])) {
            $qb
                ->andWhere('e.event = :idEvent')
                ->setParameter('idEvent', $criteria['events']);
        }

        if(!empty($criteria['status'])) {
            $qb
                ->andWhere('e.status = :statusEvent')
                ->setParameter('statusEvent', $criteria['status']);
        }

        if(!empty($criteria['durationInit'])) {
            $durationInit = \DateTime::createFromFormat('H:i:s', $criteria['durationInit']);
            $qb
                ->andWhere('p.duration >= :durationInit')
                ->setParameter('durationInit', $durationInit);
        }

        if(!empty($criteria['durationEnd'])) {
            $durationEnd = \DateTime::createFromFormat('H:i:s', $criteria['durationEnd']);
            $qb
                ->andWhere('p.duration <= :durationEnd')
                ->setParameter('durationEnd', $durationEnd);
        }

        if(!empty($criteria['dateInit'])) {
            $dateInit = \DateTime::createFromFormat('d/m/Y', $criteria['dateInit']);
            $dateInit->setTime(0, 0, 0);
            $qb
                ->andWhere('p.createdAt >= :dateInit')
                ->setParameter('dateInit', $dateInit);
        }

        if(!empty($criteria['dateEnd'])) {
            $dateEnd = \DateTime::createFromFormat('d/m/Y', $criteria['dateEnd']);
            $dateEnd->setTime(0, 0, 0);
            $qb
                ->andWhere('p.createdAt <= :dateEnd')
                ->setParameter('dateEnd', $dateEnd);
        }

        if(!empty($criteria['author'])) {
            $qb
                ->innerJoin('p.author', 'a')
                ->andWhere('a.name like :authorName')
                ->setParameter('authorName', '%'.$criteria['author'].'%');
        }

        foreach($orderBy as $name=>$order) {
            $qb->addOrderBy("p.$name", $order);
        }

        return $qb;
    }

}