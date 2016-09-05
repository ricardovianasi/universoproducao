<?php
namespace Admin\Controller\Factory;

use Admin\Controller\AbstractAdminController;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultControllerFactory implements AbstractFactoryInterface
{
	public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
	{
		$controller = $requestedName . 'Controller';
		return class_exists($controller)
			&& is_subclass_of($controller, AbstractAdminController::class);
	}

	public function createServiceWithName(ServiceLocatorInterface $controllerServiceLocator, $name, $requestedName)
	{
		$sm = $controllerServiceLocator->getServiceLocator();
		$authenticationService = $sm->get('authentication');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

		$controllerClass = $requestedName . 'Controller';
		$controller = new $controllerClass;
		$controller->setEntityManager($doctrineEntityManager);
		$controller->setAuthenticationService($authenticationService);

		if(array_key_exists('Admin\Controller\PostInterface', class_implements($controller))) {
			$postForm = $sm->get('Admin\Form\PostForm');
			$controller->setPostForm($postForm);
		}
			
		return $controller;
	}

}