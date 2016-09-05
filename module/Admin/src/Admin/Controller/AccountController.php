<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 06/01/2016
 * Time: 09:44
 */

namespace Admin\Controller;

use Admin\Form\Account\AccountInfoForm;
use Admin\Form\Account\ChangePasswordForm;
use Util\Security\Crypt;

class AccountController extends AbstractAdminController
{
	public function indexAction()
	{
		$user = $this->getCurrentUser();

		$infoForm = new AccountInfoForm();
		$changePassForm = new ChangePasswordForm();

		$infoForm->setData($user->toArray());

		return $this->getViewModel()->setVariables([
			'infoForm' => $infoForm,
			'changePassForm' => $changePassForm,
			'user' => $user,
			'tabActive' => $this->params()->fromQuery('tab', 'tab_info_form')
		]);
	}

	public function infoAction()
	{
		$user = $this->getCurrentUser();
		$infoForm = new AccountInfoForm();
		$request = $this->getRequest();

		$data = $request->getPost();
		$infoForm->setData($data);
		$infoForm->setInputFilter($user->getInputFilter());
		if($infoForm->isValid()) {
			$user->setData($data);

			$this->getEntityManager()->persist($user);
			$this->getEntityManager()->flush();

			$this->messages()->flashSuccess('Seu cadastro foi alterado com sucesso.');

			return $this->redirect()->toRoute('admin/default', ['controller'=>'account'], ['query'=>['tab'=>'tab_info_form']]);
		}
	}

	public function changePasswordAction()
	{
		$changePassForm = new ChangePasswordForm();
		$infoForm = new AccountInfoForm();
		$user = $this->getCurrentUser();

		$request = $this->getRequest();
		if($request->isPost()) {
			$changePassForm->setData($request->getPost());
			if($changePassForm->isValid()) {
				$validData = $changePassForm->getData();
				if(Crypt::getInstance()->testPass($validData['currentPassword'], $user->getPassword())) {
					$user->setPassword(Crypt::getInstance()->generateEncryptPass($validData['newPassword']));
					$user->setTempPassword(null);
					$user->setChangePasswordRequired(false);

					$this->getEntityManager()->persist($user);
					$this->getEntityManager()->flush();

					$this->messages()->flashSuccess("Senha atualizada com sucesso!");

					return $this->redirect()->toRoute('admin/default', ['controller'=>'account'], ['query'=>['tab'=>'tab_info_form']]);
				} else {
					$this->messages()->danger('A senha atual informada não está correta!');
				}
			}
		}

		$infoForm->setData($user->toArray());

		$this->getViewModel()->setTemplate('admin/account/index.phtml');
		return $this->getViewModel()->setVariables([
			'infoForm' => $infoForm,
			'changePassForm' => $changePassForm,
			'user' => $user,
			'tabActive' => 'tab_change_pass'
		]);


	}

}