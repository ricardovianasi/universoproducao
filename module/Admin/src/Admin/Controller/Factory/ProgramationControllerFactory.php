<?php
namespace Admin\Controller\Factory;

use Admin\Controller\GuideController;
use Admin\Controller\ProgramationController;
use Admin\Form\PostForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProgramationControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();
		$authenticationService = $sm->get('authentication');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

		$controller = new ProgramationController();
		$controller
			->setAuthenticationService($authenticationService)
			->setEntityManager($doctrineEntityManager);

		return $controller;
	}
}