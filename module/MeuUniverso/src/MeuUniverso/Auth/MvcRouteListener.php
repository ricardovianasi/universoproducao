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
use Application\Entity\User\User;
use MeuUniverso\Controller\RegisterController;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

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

        $routeName = $mvcEvent->getRouteMatch()->getMatchedRouteName();
        if($routeName == 'meu-universo/auth') {
            return;
        }

		$controller = $mvcEvent->getRouteMatch()->getParam('controller');
        $action = $mvcEvent->getRouteMatch()->getParam('action');

		if(strpos($controller,'MeuUniverso\Controller') === FALSE) {
			return;
		}

		if($controller == RegisterController::class) {
            if($action == 'novo' || $action == 'validar' || $action == 're-enviar-link') {
                return;
            }
        }

		$authentication = $this->getAuthenticationService();

		/** @var User|null $identity */
		$identity = null;
		if($authentication->hasIdentity()) {
			$identity = $authentication->getIdentity();
		}

		if(!$identity) {
            //Salva a url que o usuário tentou acessar
            $currentURL = $mvcEvent->getRequest()->getUriString();
            if($currentURL) {
                if(!strpos($currentURL, 'favicon')) {
                    $session = new Container();
                    $session->offsetSet('last_url_accessed_before_login', $currentURL);
                }
            }

		    //Redireciona para a tela de login
		    $url = $mvcEvent->getRouter()->assemble([], ['name'=>'meu-universo/auth']);

			$response = $mvcEvent->getResponse();
			$response->getHeaders()->addHeaderLine('Location', $url);
			$response->setStatusCode(302);
			$response->sendHeaders();
			exit();
		}

        if($controller == RegisterController::class) {
            if($action == 'editar') {
                return;
            }
        }

        if($identity->getUpdateRegisterRequired()) {
            $updateRegisterUrl = $mvcEvent->getRouter()->assemble(['action'=>'editar'], ['name'=>'meu-universo/register']);
            $updateRegisterUrl.='?atualizacao-necessaria=1';

            $response = $mvcEvent->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $updateRegisterUrl);
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit();
        }

        if($identity->getChangePasswordRequired()) {
            $changePassUrl = $mvcEvent->getRouter()->assemble(['action'=>'alterar-senha'], ['name'=>'meu-universo/auth']);
            $changePassUrl.='?atualizacao-necessaria=1';
            $response = $mvcEvent->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $changePassUrl);
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