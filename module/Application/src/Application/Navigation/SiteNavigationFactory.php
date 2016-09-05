<?php
namespace Application\Navigation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SiteNavigationFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$navigation = new SiteNavigation();
		return $navigation->createService($serviceLocator);
	}
}