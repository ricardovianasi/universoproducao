<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/12/2015
 * Time: 10:51
 */

namespace Admin\Auth\Factory;

use Admin\Auth\Authentication\DefaultAuthenticationListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultAuthenticationListenerFactory implements FactoryInterface
{
	/**
	 * @param ServiceLocatorInterface $services
	 * @return DefaultAuthenticationListener
	 */
	public function createService(ServiceLocatorInterface $services)
	{
		$listener = new DefaultAuthenticationListener();
		return $listener;
	}
}