<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/12/2015
 * Time: 10:26
 */

namespace Admin\Auth;


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

	/**
	 * @var MvcAuthEvent
	 */
	protected $mvcAuthEvent;

	/**
	 * @param MvcAuthEvent $mvcAuthEvent
	 * @param EventManagerInterface $events
	 */
	public function __construct(MvcAuthEvent $mvcAuthEvent, EventManagerInterface $events) {
		$mvcAuthEvent->setTarget($this);
		$events->attach($this);

		$this->mvcAuthEvent   = $mvcAuthEvent;
		$this->events         = $events;
	}

	/**
	 * Attach listeners
	 *
	 * @param EventManagerInterface $events
	 */
	public function attach(EventManagerInterface $events)
	{
		$this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'authentication'], -50);
		$this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'authorization'], -600);
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

		if($mvcEvent->getRouteMatch()->getParam('__NAMESPACE__') != 'Admin\Controller') {
			return true;
		}

		$routeName = $mvcEvent->getRouteMatch()->getMatchedRouteName();
		if($routeName == 'admin/auth') {
			return true;
		}

		$authentication = $this->mvcAuthEvent->getAuthenticationService();

		$identity = null;
		if($authentication->hasIdentity()) {
			$identity = $authentication->getIdentity();
		}

		if(!$identity) {
			$url = $mvcEvent->getRouter()->assemble([], ['name'=>'admin/auth']);

			$response = $mvcEvent->getResponse();
			$response->getHeaders()->addHeaderLine('Location', $url);
			$response->setStatusCode(302);
			$response->sendHeaders();
			exit();
		}

		if($identity->getChangePasswordRequired()) {
			$url = $mvcEvent->getRouter()->assemble(['action'=>'update-password'], ['name'=>'admin/auth']);

			$response = $mvcEvent->getResponse();
			$response->getHeaders()->addHeaderLine('Location', $url);
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
}