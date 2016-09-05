<?php
namespace Util\Controller;

use Util\Repository\AbstractRepository;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class AbstractController extends AbstractActionController
{
	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * @return EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}

	public function setEntityManager($entityManager)
	{
		$this->entityManager = $entityManager;
		return $this;
	}

	/**
	 * @param string $entityName
	 * @return AbstractRepository
	 * @throws \Exception
	 */
	public function getRepository($entityName)
	{
		return $this->getEntityManager()->getRepository($entityName);
	}
}