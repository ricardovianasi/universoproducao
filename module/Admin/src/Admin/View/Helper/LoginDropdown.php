<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 04/01/2016
 * Time: 07:57
 */

namespace Admin\View\Helper;

use Admin\Auth\Identity\AuthenticatedIdentity;
use Application\Entity\User\User;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Url;

class LoginDropdown extends AbstractHelper implements ServiceLocatorAwareInterface
{
	private $serviceLocator;

	public function __invoke()
	{
		$authService = null;
		if($this->getServiceLocator()->getServiceLocator()->has('authentication')) {
			$authService = $this->getServiceLocator()->getServiceLocator()->get('authentication');
		} else {
			throw new \Exception('Authentication Service not definied');
		}

		if(!$authService->hasIdentity()) {
			return;
		}

		$identity = $authService->getIdentity();
		if(!$identity) {
			return;
		}

		return $this->render($identity);
	}

	protected function render(User $user)
	{
		/**
		 * @var Url
		 */
		$urlHelper = $this->getServiceLocator()->get('url');
		$basePathHelper = $this->getServiceLocator()->get('basepath');

		$name = !empty($user->getAlias()) ? $user->getAlias() : $user->getName();

		$html = '<li class="dropdown dropdown-user">';
		$html.= '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<img alt="" class="img-circle" src="../assets/layouts/layout/img/avatar3_small.jpg" />
						<span class="username username-hide-on-mobile"> '.$name.' </span>
						<i class="fa fa-angle-down"></i>
					</a>';
		$html.= '<ul class="dropdown-menu dropdown-menu-default">';
		$html.= '<li>
					<a href="'.$urlHelper('admin/default', ['controller'=>'account']).'">
						<i class="icon-user"></i> Meu Perfil </a>
				</li>
				<li class="divider"> </li>
				<li>
					<a href="'.$urlHelper('admin/auth', ['action'=>'get-out']).'">
						<i class="icon-key"></i> Log Out </a>
				</li>';
		$html.= '</ul>';
		$html.= '</li>';

		return $html;
	}

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}
}