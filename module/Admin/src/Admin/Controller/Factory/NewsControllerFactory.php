<?php
namespace Admin\Controller\Factory;

use Admin\Controller\NewsController;
use Admin\Form\News\NewsForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NewsControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();
		$authenticationService = $sm->get('authentication');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');
		$postForm = $sm->get(NewsForm::class);

		$pageController = new NewsController();
		$pageController
			->setAuthenticationService($authenticationService)
			->setEntityManager($doctrineEntityManager)
			->setPostForm($postForm);

		return $pageController;
	}
}