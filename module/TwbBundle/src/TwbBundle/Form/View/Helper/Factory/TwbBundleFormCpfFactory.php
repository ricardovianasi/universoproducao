<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 11:08
 */

namespace TwbBundle\Form\View\Helper\Factory;


use TwbBundle\Form\View\Helper\TwbBundleFormCpf;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TwbBundleFormCpfFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$formaterHelper = null;
		if($serviceLocator->getServiceLocator()->has('cpf')) {
			$formaterHelper = $serviceLocator->getServiceLocator()->get('cpf');
		}

		return new TwbBundleFormCpf($formaterHelper);

	}

}