<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 07/12/2015
 * Time: 12:05
 */

namespace Admin\Controller;

use Admin\Form\Login\LoginForm;
use Admin\Form\Login\UpdatePassword;
use Util\Security\Crypt;
use Util\Controller\AbstractController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractController
{
	private $authenticationService;

	/**
	 * @param $authenticationService
	 */
	public function __construct($authenticationService)
	{
		$this->authenticationService = $authenticationService;
	}

	public function indexAction()
	{
		$this->layout('admin/login');
		$loginForm = new LoginForm();
		$request = $this->getRequest();

		if($request->isPost()) {
			$data = $request->getPost();
			$loginForm->setData($data);
			if($loginForm->isValid()) {
				$authService = $this->getAuthenticationService();

				$adapter = $authService->getAdapter();
				$adapter->setIdentity($data['email']);
				$adapter->setCredential($data['password']);

				$authResult = $authService->authenticate();

				if($authResult->isValid()) {

					return $this->redirect()->toRoute('admin');
				} else {
					$this->messages()->warning('Não foi possível efetuar o login. Por favor, tente novamente.');
				}
			}
		}

		return new ViewModel([
			'form' => $loginForm
		]);
	}

	public function getOutAction()
	{
		$this->getAuthService()->clearIdentity();
		return $this->redirect()->toRoute('admin/auth');
	}

	public function forgetPasswordAction()
	{
		return array();
	}

	public function problemsAction()
	{
		return array();
	}

	public function updatePasswordAction()
	{
		$this->layout('admin/login');
		$user = $this->getCurrentUser();

		if(!$user) {
			return $this->redirect()->toRoute('admin/default', [
				'controller' => 'login'
			]);
		}

		$updatePassword = new UpdatePassword();

		$error = null;

		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			$updatePassword->setData($data);
			if($updatePassword->isValid()) {
				$formData = $updatePassword->getData();
				if($user->getTempPassword() != $formData['currentPassword']) {
					$error = 'A senha atual informada não está correta!';
				}

				$user->setPassword(Crypt::getInstance()->generateEncryptPass($formData['newPassword']));
				$user->setTempPassword(null);
				$user->setChangePasswordRequired(false);

				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();

				$this->messages()->flashSuccess("Senha atualizada com sucesso!");

				return $this->redirect()->toRoute('admin/default', [
					'controller' => 'index'
				]);
			}
		}

		$updatePassword->get('email')->setValue($user->getEmail());

		return new ViewModel([
			'form' => $updatePassword,
			'error' => $error
		]);
	}

	public function getCurrentUser()
	{
		$user = $this->getAuthService()->getIdentity();
		if(!$user) {
			return null;
		}
		$this->getEntityManager()->refresh($user);
		return $user;
	}

	/**
	 * @return AuthenticationService
	 */
	public function getAuthenticationService()
	{
		return $this->authenticationService;
	}

	/**
	 * @param mixed $authenticationService
	 */
	public function setAuthenticationService($authenticationService)
	{
		$this->authenticationService = $authenticationService;
	}
}