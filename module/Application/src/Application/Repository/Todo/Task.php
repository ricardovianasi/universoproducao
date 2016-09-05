<?php
namespace Admin\Repository\Todo;

use Util\Repository\AbstractRepository;
use Admin\Entity\Todo\TodoStatus;

class Task extends AbstractRepository
{
	public function search($status=TodoStatus::OPENING, $idProject=null, \DateTime $initDate=null, \DateTime $endDate=null, $count=null) {

		$sql = $this->createQueryBuilder('T');
		$sql->andWhere('T.status = :status')
			->setParameter('status', $status);

		if(!empty($idProject)) {
			$sql->andWhere('T.project = :idProject')
				->setParameter('idProject', $idProject);
		}

		if(!empty($initDate)) {
			$sql->andWhere('T.eventDate >= :initDate')
				->setParameter('initDate', $initDate->format('Y-m-d'));
		}

		if(!empty($endDate)) {
			$sql->andWhere('T.eventDate <= :endDate')
			->setParameter('endDate', $endDate->format('Y-m-d'));
		}

		$sql->addOrderBy('T.priority', 'DESC')
			->addOrderBy('T.eventDate', 'DESC');

		if($count) {
			return $sql->getQuery()->getSingleScalarResult();
		} else {
			return $sql->getQuery()->getResult();
		}
	}
}