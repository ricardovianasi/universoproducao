<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 18/12/2017
 * Time: 11:15
 */

namespace Application\Repository\Workshop;


use Util\Repository\AbstractRepository;

class WorkshopSubscription extends AbstractRepository
{
    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $qb = $this->createQueryBuilder('p');

        if(!empty($criteria['id'])) {
            $qb->andWhere('id = :id')
                ->setParameter('p.id', $criteria['id']);
        }

        if(!empty($criteria['event'])) {
            $qb->andWhere('p.event = :idEvent')
                ->setParameter('idEvent', $criteria['event']);
        }

        if(!empty($criteria['workshop'])) {
            $qb->andWhere('p.workshop = :idWorkshop')
                ->setParameter('idWorkshop', $criteria['workshop']);
        }

        if(!empty($criteria['user_search'])) {
            $qb->innerJoin('p.user', 'u')
                ->andWhere('u.name like :use')
                ->setParameter('user', '%'.$criteria['user_search'].'%');
        }

        if(!empty($criteria['status'])) {
            $qb
                ->andWhere('p.status = :status')
                ->setParameter('status', $criteria['status']);
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

        $qb
            ->leftJoin('p.pontuations', 'po')
            ->addSelect('SUM(po.value) as HIDDEN total_pontuation')
            ->groupBy('p.id')
            ->orderBy('total_pontuation', 'DESC');

        return $qb;
    }

}