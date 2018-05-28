<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 14/05/2018
 * Time: 14:45
 */

namespace Application\Repository\Seminar;

use Util\Repository\AbstractRepository;

class SeminarSubscription extends AbstractRepository
{
    public function getTotalSubscription($registration)
    {
        $qb = $this->createQueryBuilder('p');
        $count = $qb->select('count(p) as total')
            ->andWhere('p.registration = :idRegistration')
            ->setParameter('idRegistration', $registration)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }

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

        if(!empty($criteria['category'])) {
            $qb->andWhere('p.category = :idcategory')
                ->setParameter('idcategory', $criteria['category']);
        }

        if(!empty($criteria['user_search'])) {
            $qb->innerJoin('p.user', 'u')
                ->andWhere('u.name like :user')
                ->setParameter('user', '%'.$criteria['user_search'].'%');
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
            ->orderBy('p.createdAt', 'DESC');

        return $qb;
    }
}