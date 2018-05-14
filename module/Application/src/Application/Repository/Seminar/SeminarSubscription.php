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
}