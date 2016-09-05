<?php
namespace Admin\Form;

trait EntityManagerTrait
{
	protected $entityManager;

	/**
	 * @return \Doctrine\Orm\EntityManager
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

	public function getRepository($entityClass)
	{
		if(!$entityClass) {
			return null;
		}

		return $this
			->getEntityManager()
			->getRepository($entityClass);
	}
}