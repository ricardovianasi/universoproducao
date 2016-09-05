<?php
namespace Admin\Controller\Factory;

use Admin\Controller\GuideController;
use Admin\Form\PostForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GuideControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$sm = $serviceLocator->getServiceLocator();
		$authenticationService = $sm->get('authentication');
		$doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');
        $postForm = $sm->get(PostForm::class);

		$guide = new GuideController();
		$guide
			->setAuthenticationService($authenticationService)
			->setEntityManager($doctrineEntityManager)
            ->setPostForm($postForm);

		return $guide;
	}
}