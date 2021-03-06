<?php
namespace Admin\Controller\Factory;


use Admin\Controller\BannerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BannerControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');
        $authenticationService = $sm->get('authentication');

		$controller = new BannerController();
		$controller->setEntityManager($doctrineEntityManager);
		$controller->setAuthenticationService($authenticationService);

		return $controller;
	}
}