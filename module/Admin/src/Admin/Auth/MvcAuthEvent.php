<?php
namespace Admin\Auth;

use Admin\Auth\Identity\IdentityInterface;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;

class MvcAuthEvent extends Event
{
	const EVENT_AUTHENTICATION = 'authentication';
	const EVENT_AUTHENTICATION_POST = 'authentication.post';
	const EVENT_AUTHORIZATION = 'authorization';
	const EVENT_AUTHORIZATION_POST = 'authorization.post';

	/**
	 * @var MvcEvent
	 */
	protected $mvcEvent;

	/**
	 * @var mixed
	 */
	protected $authentication;

	/**
	 * @var Result
	 */
	protected $authenticationResult = null;

	/**
	 * @var mixed
	 */
	protected $authorization;

	/**
	 * Whether or not authorization has completed/succeeded
	 * @var bool
	 */
	protected $authorized = false;

	/**
	 * The resource used for authorization queries
	 *
	 * @var mixed
	 */
	protected $resource;

	/**
	 * @param MvcEvent $mvcEvent
	 * @param mixed    $authentication
	 * @param mixed    $authorization
	 */
	public function __construct(MvcEvent $mvcEvent, $authentication, $authorization=null)
	{
		$this->mvcEvent = $mvcEvent;
		$this->authentication = $authentication;
		$this->authorization = $authorization;
	}

	/**
	 * @return mixed
	 */
	public function getAuthenticationService()
	{
		return $this->authentication;
	}

	/**
	 * @return mixed
	 */
	public function getAuthorizationService()
	{
		return $this->authorization;
	}

	/**
	 * @return MvcEvent
	 */
	public function getMvcEvent()
	{
		return $this->mvcEvent;
	}
}