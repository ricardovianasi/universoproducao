<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 09/12/2015
 * Time: 10:00
 */

namespace Admin\Controller\Plugin;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Slugify extends AbstractPlugin
{
	/**
	 * Doctrine EntityManager object
	 *
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * Slug property name
	 *
	 * @var string
	 */
	private $entityProperty = 'slug';

	/**
	 * Create slug
	 *
	 * @param $str
	 * @param bool|false $useDataSource
	 * @param string $entity
	 * @param int $escapeId
	 * @return string
	 * @throws \Exception
	 */
	public function create($str, $useDataSource=false, $entity=null, $siteId=null, $escapeId=null)
	{
		if(!$useDataSource) {
			return self::parse($str);
		}

		$originalStr = $str;
		$count = 1;
		do {
			$slug = self::parse($str);
			$str = $originalStr . ' ' . $count++;
		} while($this->slugExists($slug, $entity, $siteId, $escapeId));

		return $slug;
	}

	/**
	 * Verify slug exists on data base
	 *
	 * @param $str
	 * @param $entity
	 * @param int $escapeId
	 * @return int
	 * @throws \Exception
	 */
	public function slugExists($str, $entity, $siteId=null, $escapeId=null)
	{
		if(!$this->getEntityManager()) {
			throw new \Exception('Entity Manager Object not found');
		}

		$qb = $this->getEntityManager()->getRepository($entity)->createQueryBuilder('p')
			->select('count(p)')
			->andWhere('p.' . $this->entityProperty . ' = :str')
			->setParameter('str', $str);

		if($siteId) {
			$qb->andWhere('p.site = :idSite')
				->setParameter('idSite', $siteId);
		}

		if($escapeId) {
			$qb->andWhere('p.id != :id')
				->setParameter('id', $escapeId);
		}

		return $qb->getQuery()->getSingleScalarResult();
	}

	/**
	 * Remove special characters and replace space to dash ( - )
	 *
	 * @param string $str
	 * @return string
	 */
	public static function parse($str)
	{
		return strtolower(
			trim(
				preg_replace('~[^0-9a-z]+~i', '-',
					html_entity_decode(
						preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i',
							'$1',
							htmlentities($str, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'
					)
				), '-'
			)
		);
	}

	/**
	 * @return EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}

	public function setEntityManager($em)
	{
		$this->entityManager = $em;
		return $this;
	}
}