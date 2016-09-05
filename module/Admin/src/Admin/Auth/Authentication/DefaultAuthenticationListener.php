<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/12/2015
 * Time: 10:44
 */

namespace Admin\Auth\Authentication;

use Admin\Auth\Identity;
use Admin\Auth\MvcAuthEvent;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;

class DefaultAuthenticationListener
{
	/**
	 * Listen to the authentication event
	 *
	 * @param MvcAuthEvent $mvcAuthEvent
	 * @return null|Identity\IdentityInterface
	 */
	public function __invoke(MvcAuthEvent $mvcAuthEvent)
	{
		$authentication = $mvcAuthEvent->getAuthenticationService();
		$mvcEvent = $mvcAuthEvent->getMvcEvent();
		$request  = $mvcEvent->getRequest();
		$response = $mvcEvent->getResponse();

		if (!$request instanceof HttpRequest || $request->isOptions()) {
			return;
		}

		$result = null;
		if($authentication->hasIdentity()) {
			$result = $authentication->getIdentity();
			if($result) {
				if(!$result instanceof Identity\IdentityInterface) {
					$result = new Identity\AuthenticatedIdentity($result);
				}
			} else {
				$result = new Identity\GuestIdentity();
			}
		}

		$mvcEvent->setParam('Auth\Identity', $result);
		return $result;
	}
}