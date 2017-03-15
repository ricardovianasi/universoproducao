<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 20/02/2016
 * Time: 11:35
 */

namespace Admin\Controller;

use Admin\Form\User\ResetPasswordForm;
use Admin\Form\User\UserForm;
use Admin\Form\User\UserSearch;
use Application\Entity\AdminUser\User;
use Util\Security\Crypt;

class AdminUserController extends AbstractAdminController
	implements CrudInterface

{
	public function indexAction()
	{
		$searchForm = new UserSearch();
		$dataAttr = $this->params()->fromQuery();
		$searchForm->setData($dataAttr);
		$searchForm->isValid();

		$users = $this->search(User::class, $searchForm->getData());

		$this->getViewModel()->setVariables([
			'searchForm' => $searchForm,
			'users' => $users
		]);

		return $this->getViewModel();
	}

	public function createAction($data)
	{
		return $this->persist($data);
	}

	public function updateAction($id, $data)
	{
		return $this->persist($data, $id);
	}

	public function deleteAction($id)
	{
		$user = $this->getRepository(User::class)->find($id);

		$this->getEntityManager()->remove($user);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('O usuário foi excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'admin-user']);
	}

	public function persist($data, $id = null)
	{
		$form = $this->getServiceLocator()->get(UserForm::class);
		$resetPassForm = new ResetPasswordForm();
		$tabActive = $this->params()->fromQuery('tab', 'tab_form');


		if($id) {
			$user = $this->getRepository(User::class)->find($id);
		} else {
			$user = new User();

			$tempPass = Crypt::makePassword();

			$user->setTempPassword($tempPass);
			$user->setPassword(Crypt::getInstance()->generateEncryptPass($tempPass));
			$user->setChangePasswordRequired(true);
			$user->setConfirmedRegister(false);
		}

		if($this->getRequest()->isPost()) {
			$tabActive = 'tab_form';
			$form->setInputFilter($user->getInputFilter());
			$form->setData($data);
			if($form->isValid()) {
				$validData = $form->getData();

				$user->setData($this->prepareDataPost(User::class, $validData));

				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Usuário atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Usuário criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'admin-user',
						'action' => 'update',
						'id' => $user->getId()
					]);
				}
			}
		} else {
			$form->setData($user->toArray());
		}

		$resetPassForm->setData($user->toArray());

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'user' => $user,
			'resetPassForm' => $resetPassForm,
			'tabActive' => $tabActive
		]);
	}

	public function resetPasswordAction()
	{
		$userId = $this->params()->fromRoute('id');
		$user = $this->getRepository(User::class)->find($userId);

		$resetPassForm = new ResetPasswordForm();
		$form = $this->getServiceLocator()->get(UserForm::class);

		if($this->getRequest()->isPost()) {
			$resetPassForm->setData($this->getRequest()->getPost());
			if($resetPassForm->isValid()) {
				$data = $resetPassForm->getData();
				$user->setTempPassword($data['temp_password']);
				$user->setPassword(Crypt::getInstance()->generateEncryptPass($data['temp_password']));
				$user->setChangePasswordRequired(true);

				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();

				$this->messages()->success("A senha foi resetada com sucesso!");

				return $this->redirect()->toRoute('admin/default', [
					'controller' => 'admin-user',
					'action' => 'update',
					'id' => $user->getId()], ['query'=>['tab'=>'tab_pass']]);
			}
		}

		$this->getViewModel()->setTemplate('admin/admin-user/update.phtml');
		$form->setData($user->toArray());
		$resetPassForm->setData($user->toArray());

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'user' => $user,
			'resetPassForm' => $resetPassForm,
			'tabActive' => 'tab_pass'
		]);
	}
}