<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/03/2018
 * Time: 12:19
 */
namespace Application\Repository\EducationalProject;

use Util\Repository\AbstractRepository;

class EducationalProject extends AbstractRepository
{

    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $qb = $this->createQueryBuilder('p');

        if(!empty($criteria['title'])) {
            $qb
                ->andWhere('p.title like :title')
                ->setParameter('title', '%'.$criteria['title'].'%');
        }

        if(!empty($criteria['id'])) {
            $qb->andWhere('id = :id')
                ->setParameter('p.id', $criteria['id']);
        }

        if(!empty($criteria['event'])) {
            $qb->andWhere('p.event = :idEvent')
                ->setParameter('idEvent', $criteria['event']);
        }

        if(!empty($criteria['user'])) {
            $qb->innerJoin('p.user', 'u')
                ->andWhere('u.name like :user')
                ->setParameter('user', '%'.$criteria['user'].'%');
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

        $qb->orderBy('p.createdAt', 'DESC');

        return $qb;
    }
}