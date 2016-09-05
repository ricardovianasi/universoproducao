<?php

namespace Admin\Controller\Factory;

use Admin\Controller\LoginController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $controllerServiceLocator)
	{
		$sm = $controllerServiceLocator->getServiceLocator();

		$authenticationService = $sm->get('authentication');
		
		$controller = new LoginController($authenticationService);
		return $controller;
	}


}