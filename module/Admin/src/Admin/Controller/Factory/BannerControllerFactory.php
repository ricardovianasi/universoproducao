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

		$controller = new BannerController();
		$controller->setEntityManager($doctrineEntityManager);

		return $controller;
	}
}