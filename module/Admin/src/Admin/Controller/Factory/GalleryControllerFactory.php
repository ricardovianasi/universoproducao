<?php
namespace Admin\Controller\Factory;

use Admin\Controller\BannerController;
use Admin\Controller\GalleryController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GalleryControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();

		$viewHelper = $sm->get('ViewHelperManager');
		$adminGalleryViewHelper = $viewHelper->get('adminGallery');
        $authenticationService = $sm->get('authentication');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

		$controller = new GalleryController();
		$controller->setAdminGalleryHelper($adminGalleryViewHelper);
		$controller->setAuthenticationService($authenticationService);
		$controller->setEntityManager($doctrineEntityManager);

		return $controller;
	}
}