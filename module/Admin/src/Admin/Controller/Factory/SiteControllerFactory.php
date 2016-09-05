<?php
namespace Admin\Controller\Factory;


use Admin\Controller\SiteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SiteControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();

		$viewHelper = $sm->get('ViewHelperManager');
		$adminPostSiteViewHelper = $viewHelper->get('adminPostSiteView');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

		$controller = new SiteController();
		$controller->setEntityManager($doctrineEntityManager);
		$controller->setAdminPostSiteViewHelper($adminPostSiteViewHelper);

		return $controller;
	}
}