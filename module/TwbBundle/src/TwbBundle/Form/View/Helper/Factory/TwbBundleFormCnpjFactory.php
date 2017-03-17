<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 11:08
 */

namespace TwbBundle\Form\View\Helper\Factory;

use TwbBundle\Form\View\Helper\TwbBundleFormCnpj;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TwbBundleFormCnpjFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$formaterHelper = null;
		if($serviceLocator->getServiceLocator()->has('cnpj')) {
			$formaterHelper = $serviceLocator->getServiceLocator()->get('cnpj');
		}

		return new TwbBundleFormCnpj($formaterHelper);

	}

}