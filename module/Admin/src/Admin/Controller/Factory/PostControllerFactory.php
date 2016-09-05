<?php
namespace Admin\Controller\Factory;

use Admin\Controller\PostInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostControllerFactory implements AbstractFactoryInterface
{
	public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
	{
		$controller = $requestedName . 'Controller';
		return
			class_exists($controller)
			&& is_subclass_of($controller, AbstractAdminController::class)
			&& array_key_exists(PostInterface::class, class_implements($controller));
	}

	public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
	{
		$controllerClass = $requestedName . 'Controller';
		$controller = new $controllerClass;

		$sm = $serviceLocator->getServiceLocator();
		$authenticationService = $sm->get('authentication');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

		$controller->setEntityManager($doctrineEntityManager);
		$controller->setAuthenticationService($authenticationService);

		
	}
}