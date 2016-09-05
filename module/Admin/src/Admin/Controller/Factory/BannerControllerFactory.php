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

		$viewHelper = $sm->get('ViewHelperManager');
		$adminBannerViewHelper = $viewHelper->get('adminBanner');

		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

		$controller = new BannerController();
		$controller->setAdminBannerHelper($adminBannerViewHelper);
		$controller->setEntityManager($doctrineEntityManager);

		return $controller;
	}
}