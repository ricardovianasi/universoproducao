<?php
namespace Admin\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\BasePath;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class AdminTemplateBasePath extends AbstractHelper implements ServiceLocatorAwareInterface
{
	const CONFIG_KEY = 'template_base_path';

	private $serviceLocator;

	public function __invoke($file = null, $module='admin')
	{
		$config = $this->getServiceManager()->has('Config') ? $this->getServiceManager()->get('Config') : [];

		$basePath = "";
		if (isset($config['view_manager']) && isset($config['view_manager']['base_path'])) {
			$basePath = $config['view_manager']['base_path'];
		} else {
			$request = $this->getServiceManager()->get('Request');
			if (is_callable([$request, 'getBasePath'])) {
				$basePath = $request->getBasePath();
			}
		}

		if (null === $basePath) {
			throw new \RuntimeException('No base path provided');
		}

		if(isset($config[self::CONFIG_KEY]) && $config[self::CONFIG_KEY][$module]) {
			$basePath.= '/' . ltrim($config[self::CONFIG_KEY][$module], '/');
		}

		if (null !== $file) {
			$file = '/' . ltrim($file, '/');
		}

		return $basePath . $file;

	}

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	public function getServiceManager()
	{
		if($this->serviceLocator) {
			return $this->serviceLocator->getServiceLocator();
		}

		return null;
	}
}