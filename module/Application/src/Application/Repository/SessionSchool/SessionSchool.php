<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/04/2018
 * Time: 10:42
 */

namespace Application\Repository\SessionSchool;


use Application\Entity\Programing\Programing;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
use Util\Repository\AbstractRepository;

class SessionSchool extends AbstractRepository
{

    public function hasAvailableSubscriptions($idSessionProg)
    {
        $totalSubscriptions = $this->getTotalSubscriptionsSession($idSessionProg);

        /** @var Programing $prog */
        $prog = $this
            ->getEntityManager()
            ->getRepository(Programing::class)
            ->find($idSessionProg);

        if($prog && $prog->getAvailablePlaces() > $totalSubscriptions) {
            return true;
        } else {
            return false;
        }

    }

    public function getTotalSubscriptionsSession($idSessionProg)
    {

        return $this
            ->getEntityManager()
            ->getRepository(SessionSchoolSubscription::class)
            ->createQueryBuilder('s')
            ->select('SUM(s.participants) as total')
            ->andWhere('s.sessionProgramming = :idSessionProg')
            ->setParameter('idSessionProg', $idSessionProg)
            ->getQuery()
            ->getSingleScalarResult();
    }
}