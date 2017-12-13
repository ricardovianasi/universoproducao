<?php
namespace Util\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

abstract class AbstractRepository extends EntityRepository
{
	const QB_ALIAS = 'q';

	protected $defaultPageSize = 25;

	public function directUpdate($entity) {
		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();
		return $entity;
	}

	public function remove($enitty) {
		$this->getEntityManager()->remove($enitty);
		$this->getEntityManager()->flush();
	}

	/**
	 * Prepara os critérios de busca
	 * @param array $criteria
	 * @return QueryBuilder
	 */
	public function prepareSearch($criteria=[], $orderBy=[])
	{
		$queryBuilder = $this->createQueryBuilder(self::QB_ALIAS);

		//Relacionamento com site
		if(!empty($criteria['site'])) {
			$queryBuilder->andWhere(self::QB_ALIAS.'.site = :site')
				->setParameter('site', $criteria['site']);
		}
		unset($criteria['site']);

		//Data data de inicio e fim
		if(!empty($criteria['dateInit'])) {
			$queryBuilder->andWhere(self::QB_ALIAS.'.postDate >= :dateInit')
				->setParameter('dateInit', $criteria['dateInit']);
		}
		unset($criteria['dateInit']);

		if(!empty($criteria['dateEnd'])) {
			$queryBuilder->andWhere(self::QB_ALIAS.'.postDate <= :dateEnd')
					->setParameter('dateEnd', $criteria['dateEnd']);
		}
		unset($criteria['dateEnd']);

		foreach ($criteria as $key=>$value) {
			if(!empty($value)) {

				if(is_numeric($value)) {
					$sqlCondition = '=';
				} else {
					$sqlCondition = 'like';
					$value = "%$value%";
				}

				$queryBuilder->andWhere(self::QB_ALIAS . ".$key $sqlCondition :$key")
					->setParameter($key, $value);
			}
		}

		foreach($orderBy as $name=>$order) {
			$queryBuilder->addOrderBy(self::QB_ALIAS.".$name", $order);
		}

		return $queryBuilder;
	}

	public function search($criteria=array(), $orderBy=[], $igonorePagination=false, $currentPage=1, $pageSize=null)
	{
	    if(!$pageSize) {
	        $pageSize = $this->defaultPageSize;
        }

		$queryBuilder = $this->prepareSearch($criteria, $orderBy);

		if($igonorePagination) {
            return $queryBuilder->getQuery()->getResult();
        } else {
            $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder, false));
            $paginator = new Paginator($adapter);
            $paginator->setDefaultItemCountPerPage($pageSize);
            $paginator->setCurrentPageNumber($currentPage);
            return $paginator;
        }
	}
}