<?php
namespace Admin\Controller\Factory;

use Admin\Controller\BannerController;
use Admin\Controller\MenuController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();

		$viewHelper = $sm->get('ViewHelperManager');
		$adminMenuViewHelper = $viewHelper->get('adminMenu');
		$adminMenuPagesViewHelper = $viewHelper->get('adminMenuPages');

		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

		$controller = new MenuController();
		$controller->setAdminMenuPagesViewHelper($adminMenuPagesViewHelper);
		$controller->setAdminMenuViewHelper($adminMenuViewHelper);
		$controller->setEntityManager($doctrineEntityManager);

		return $controller;
	}
}