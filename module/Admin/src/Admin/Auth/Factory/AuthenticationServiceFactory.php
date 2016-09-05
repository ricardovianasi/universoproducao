<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/12/2015
 * Time: 10:51
 */

namespace Admin\Auth\Factory;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\NonPersistent;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
	/**
	 * Create service
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return AuthenticationService
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		if($serviceLocator->has('doctrine.authenticationservice.orm_default')) {
			return $serviceLocator->get('doctrine.authenticationservice.orm_default');
		} else {
			// @todo This should be configurable, or replacable?
			return new AuthenticationService(new NonPersistent());
		}
	}
}