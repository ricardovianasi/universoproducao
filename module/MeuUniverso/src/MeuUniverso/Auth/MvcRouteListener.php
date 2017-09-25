<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/12/2015
 * Time: 10:26
 */

namespace MeuUniverso\Auth;

use Admin\Auth\Identity\GuestIdentity;
use Admin\Auth\Identity\IdentityInterface;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;

class MvcRouteListener extends AbstractListenerAggregate
{
	/**
	 * @var EventManagerInterface
	 */
	protected $events;

	protected $authenticationService;

	/**
	 * @param EventManagerInterface $events
	 */
	public function __construct(EventManagerInterface $events, $authenticationService) {
		$events->attach($this);

		$this->events = $events;
		$this->authenticationService = $authenticationService;
	}

	/**
	 * Attach listeners
	 *
	 * @param EventManagerInterface $events
	 */
	public function attach(EventManagerInterface $events)
	{
		$this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'authentication'], -50);
	}

	/**
	 * Trigger the authentication event
	 *
	 * @param MvcEvent $mvcEvent
	 * @return null|Response
	 */
	public function authentication(MvcEvent $mvcEvent)
	{
		if (!$mvcEvent->getRequest() instanceof HttpRequest || $mvcEvent->getRequest()->isOptions()) {
			return;
		}

		$mvcEvent->getController();
		$mvcEvent->getTarget();

		$controller = $mvcEvent->getRouteMatch()->getParam('controller');

		if(strpos($controller,'MeuUniverso\Controller') === FALSE) {
			return;
		}

		$routeName = $mvcEvent->getRouteMatch()->getMatchedRouteName();
		if($routeName == 'meu-universo/auth') {
			return;
		}

		$authentication = $this->getAuthenticationService();

		$identity = null;
		if($authentication->hasIdentity()) {
			$identity = $authentication->getIdentity();
		}

		if(!$identity) {
			$url = $mvcEvent->getRouter()->assemble([], ['name'=>'meu-universo/auth']);

			$response = $mvcEvent->getResponse();
			$response->getHeaders()->addHeaderLine('Location', $url);
			$response->setStatusCode(302);
			$response->sendHeaders();
			exit();
		}
	}

	/**
	 * Trigger the authorization event
	 *
	 * @param MvcEvent $mvcEvent
	 * @return null|Response
	 */
	public function authorization(MvcEvent $mvcEvent)
	{
		return true;
	}

	public function getAuthenticationService()
    {
        return $this->authenticationService;
    }
}