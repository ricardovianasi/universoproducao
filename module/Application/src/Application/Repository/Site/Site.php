<?php
namespace Application\Repository\Site;

use Util\Repository\AbstractRepository;

class Site extends AbstractRepository
{
	public function findEnabledSites()
	{
		return $this->findBy(['status' => true], ['name' => 'ASC']);
	}

	public function getSitesByEventType($eventType)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->innerJoin('p.event', 'e')
            ->andWhere('e.type = :eventType')
            ->setParameter('eventType', $eventType);

        return $qb->getQuery()->getResult();
    }
}