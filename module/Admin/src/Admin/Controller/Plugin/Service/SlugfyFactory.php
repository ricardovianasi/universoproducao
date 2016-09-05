<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 09/12/2015
 * Time: 10:38
 */
namespace Admin\Controller\Plugin\Service;

use Admin\Controller\Plugin\Slugify;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SlugfyFactory implements FactoryInterface
{
	/**
	 * {@inheritDoc}
	 *
	 * @return \Zend\Mvc\Controller\Plugin\Identity
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$services = $serviceLocator->getServiceLocator();
		$helper = new Slugify();

		if($services->has('doctrine.entitymanager.orm_default')) {
			$helper->setEntityManager($services->get('doctrine.entitymanager.orm_default'));
		}

		return $helper;
	}
}