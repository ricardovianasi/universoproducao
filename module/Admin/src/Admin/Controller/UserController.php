<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 04/12/2015
 * Time: 09:43
 */

namespace Admin\Controller;

use Admin\Form\ExternalUser\ChangePassForm;
use Admin\Form\ExternalUser\UserForm;
use Admin\Form\ExternalUser\UserSearch;
use Application\Entity\User\User;
use Util\Security\Crypt;

class UserController extends AbstractAdminController implements CrudInterface
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

	public function persist($data, $id = null)
	{
		$form = $this->getServiceLocator()->get(UserForm::class);
		$passForm = new ChangePassForm();

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
					$this->messages()->flashSuccess("Usuário criada com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'user',
						'action' => 'update',
						'id' => $user->getId()
					]);
				}
			}
		} else {
			$form->setData($user->toArray());
		}

		$passForm->setData($user->toArray());

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'passForm' => $passForm,
			'user' => $user
		]);
	}

	public function deleteAction($id)
	{
		$user = $this->getRepository(User::class)->find($id);

		$this->getEntityManager()->remove($user);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('O usuário foi excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'user']);
	}

	public function changePasswordAction()
	{
		$userId = $this->params()->fromRoute($this->getIdentifierName());
		$user = $this->getRepository(User::class)->find($userId);
		if(!$user) {
			$this->messages()->flashError("Usuário não encontrado!");
			return $this->redirect()->toRoute('admin/default', [
				'controller' => 'user',
				'action' => 'indeex'
			]);
		}

		$tempPass = $this->params()->fromPost('temp-pass');
		$user->setPassword(Crypt::getInstance()->generateEncryptPass($tempPass));
		$user->setChangePasswordRequired(true);

		$this->getEntityManager()->persist($user);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess("A senha do usuário foi alterada com sucesso.");
		return $this->redirect()->toRoute('admin/default', [
			'controller' => 'user',
			'action' => 'update',
			'id' => $user->getId()
		]);
	}
}