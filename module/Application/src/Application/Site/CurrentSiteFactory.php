<?php
namespace Application\Site;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CurrentSiteFactory implements FactoryInterface
{
	private $siteId;

	public function __construct($siteId)
	{
		$this->siteId = $siteId;
	}

	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		if(!$this->siteId) {
			return null;
		}

		$entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

	}
}