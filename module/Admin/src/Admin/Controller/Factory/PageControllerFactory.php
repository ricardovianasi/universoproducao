<?php
namespace Admin\Controller\Factory;

use Admin\Controller\PageController;
use Admin\Form\Page\PageForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();
		$authenticationService = $sm->get('authentication');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');
		$pageForm = $sm->get(PageForm::class);

		$pageController = new PageController();
		$pageController
			->setAuthenticationService($authenticationService)
			->setEntityManager($doctrineEntityManager)
			->setPageForm($pageForm);

		return $pageController;
	}
}