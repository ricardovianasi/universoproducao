<?php

namespace Admin\Auth\Identity;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\InjectApplicationEventInterface;


class IdentityPlugin extends AbstractPlugin
{
	public function __invoke()
	{
		$controller = $this->getController();
		if (! $controller instanceof InjectApplicationEventInterface) {
			return new GuestIdentity();
		}

		$event    = $controller->getEvent();
		$identity = $event->getParam(__NAMESPACE__);

		if (! $identity instanceof IdentityInterface) {
			return new GuestIdentity();
		}

		return $identity;
	}
}