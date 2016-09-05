<?php
namespace Application\Site;

use Application\Entity\Site\Site;
use Doctrine\ORM\EntityManager;

class CurrentSite
{
	public $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @return EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}

	public function getSite($id)
	{
		return $this->getEntityManager()
			->getRepository(Site::class)
			->find($id);
	}
}