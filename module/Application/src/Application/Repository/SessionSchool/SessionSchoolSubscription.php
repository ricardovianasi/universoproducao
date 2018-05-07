<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/04/2018
 * Time: 10:42
 */

namespace Application\Repository\SessionSchool;

use Util\Repository\AbstractRepository;

class SessionSchoolSubscription extends AbstractRepository
{
    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $qb = $this->createQueryBuilder('p');

        if(!empty($criteria['event'])) {
            $qb
                ->andWhere('p.event = :idEvent')
                ->setParameter('idEvent', $criteria['event']);
        }

        if(!empty($criteria['instituition_social_name'])) {
            $qb
                ->leftJoin('p.instituition', 'i')
                ->andWhere('i.socialName like :instituition_social_name')
                ->setParameter("instituition_social_name", '%'.$criteria['instituition_social_name'].'%');
        }

        if(!empty($criteria['session'])) {
            $qb
                ->andWhere('p.sessionProgramming = :sessionProgramming')
                ->setParameter('sessionProgramming', $criteria['session']);
        }

        $qb->orderBy('p.createdAt', 'DESC');
        return $qb;
    }
}