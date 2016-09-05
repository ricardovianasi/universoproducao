<?php
namespace Admin\Controller\Factory;

use Admin\Controller\NewsController;
use Admin\Controller\PageController;
use Admin\Controller\TvController;
use Admin\Form\Page\PageForm;
use Admin\Form\PostForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TvControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();
		$authenticationService = $sm->get('authentication');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');
		$postForm = $sm->get(PostForm::class);

		$tvController = new TvController();
		$tvController
			->setAuthenticationService($authenticationService)
			->setEntityManager($doctrineEntityManager)
			->setPostForm($postForm);

		return $tvController;
	}
}