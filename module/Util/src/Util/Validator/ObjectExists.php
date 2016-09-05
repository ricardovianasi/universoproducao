<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 24/02/2016
 * Time: 10:47
 */

namespace Util\Validator;


use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class ObjectExists extends AbstractValidator implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
	}

	public function getServiceLocator()
	{
	}

	public function isValid($value)
	{
	}
}