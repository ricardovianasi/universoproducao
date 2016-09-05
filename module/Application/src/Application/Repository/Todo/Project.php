<?php
namespace Admin\Repository\Todo;

use Util\Repository\AbstractRepository;
use Admin\Entity\Todo\TodoStatus;
use Doctrine\ORM\Query\ResultSetMapping;

class Project extends AbstractRepository
{
	public function findProjectList($idUser, $status=TodoStatus::OPENING) {
		$sql = "
			SELECT P.id, P.name, P.status, count(T.id) as total_task FROM project P
			LEFT JOIN task T on T.id_project = P.id and T.status = 'opening' AND T.id_user_associete = $idUser  WHERE 1 = 1 ";

		if(!empty($status)) {
			$sql.= "AND P.status = '$status' ";
		}

		$sql.= " GROUP BY P.id ORDER BY P.created_at DESC";

		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('id','id');
		$rsm->addScalarResult('name', 'name');
		$rsm->addScalarResult('status', 'status');
		$rsm->addScalarResult('total_task', 'total_task');
		$native = $this->getEntityManager()->createNativeQuery($sql, $rsm);
		return $native->getResult();
	}
}