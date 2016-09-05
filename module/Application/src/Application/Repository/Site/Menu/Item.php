<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 06/03/2016
 * Time: 14:17
 */

namespace Application\Repository\Site\Menu;


use Util\Repository\AbstractRepository;

class Item extends AbstractRepository
{
	public function findAllByMenu($menuId)
	{
		$qb = $this->createQueryBuilder('p');
		$qb->select('p')
			->andWhere('p.parent is NULL')
			->andWhere('p.menu = :menuId')
			->setParameter('menuId', $menuId)
			->orderBy('p.order', 'ASC');

		return $qb->getQuery()->getResult();
	}

}