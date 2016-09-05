<?php
namespace Cineop\Controller\Factory;

use Admin\Controller\AbstractAdminController;
use Util\Controller\AbstractController;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultControllerFactory implements AbstractFactoryInterface
{
	public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
	{
		$controller = $requestedName . 'Controller';
		return class_exists($controller)
			&& is_subclass_of($controller, AbstractController::class);
	}

	public function createServiceWithName(ServiceLocatorInterface $controllerServiceLocator, $name, $requestedName)
	{
		$sm = $controllerServiceLocator->getServiceLocator();
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

		$controllerClass = $requestedName . 'Controller';
		$controller = new $controllerClass;
		$controller->setEntityManager($doctrineEntityManager);
			
		return $controller;
	}

}