<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 04/12/2015
 * Time: 09:43
 */

namespace Admin\Controller;

use Admin\Form\ExternalUser\ChangePassForm;
use Admin\Form\ExternalUser\CompanyForm;
use Admin\Form\ExternalUser\DependentForm;
use Admin\Form\ExternalUser\PhoneForm;
use Admin\Form\ExternalUser\UserForm;
use Admin\Form\ExternalUser\UserSearch;
use Application\Entity\City;
use Application\Entity\Phone\Phone;
use Application\Entity\User\Dependent;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
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
		$form = new UserForm($this->getEntityManager());
		$passForm = new ChangePassForm();
		$phoneForm = new PhoneForm();
		$dependentForm = new DependentForm();

		if($id) {
			$user = $this->getRepository(User::class)->find($id);
		} else {
			$user = new User();
			//$user->setPassword(Crypt::getInstance()->generateEncryptPass());
			$user->setChangePasswordRequired(true);
			$user->setConfirmedRegister(false);
		}

		if($this->getRequest()->isPost()) {
			$form->setInputFilter($user->getInputFilter());
			$form->setData($data);
			if($form->isValid()) {

			    //Salva o usuário
				$validData = $form->getData();

				$phones = new ArrayCollection();
				foreach ($user->getPhones() as $ph) {
				    $this->getEntityManager()->remove($ph);
                }
				if(!empty($validData['phones'])) {
                    foreach ($validData['phones'] as $ph) {
                        $phone = new Phone($ph);
                        $phones->add($phone);
                    }
                }
                unset($validData['phones']);
                $user->setPhones($phones);

                $dependents = new ArrayCollection();
                foreach ($user->getDependents() as $de) {
                    $this->getEntityManager()->remove($de);
                }
                if(!empty($validData['dependents'])) {
                    foreach ($validData['dependents'] as $d) {
                        if(isset($d['id'])) {
                            $dep = $this->getRepository(Dependent::class)->find($d['id']);
                        } else {
                            $dep = new Dependent($d);
                            $dep->setUser($user);
                        }
                        $dependents->add($dep);
                    }
                }
                $user->setDependents($dependents);
                unset($validData['dependents']);

                if(isset($validData['city'])) {
                    $city = $this->getRepository(City::Class)->find($validData['city']);
                    $validData['city'] = $city;
                }

                $user->setData($user);

				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Usuário atualizado com sucesso!");
                    $this->userLog()->log($user, 'Cadastro do usuário atualizado');
				} else {
					$this->messages()->flashSuccess("Usuário criada com sucesso!");
                    $this->userLog()->log($user, 'Cadastro do usuário criado');
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
            'phoneForm' => $phoneForm,
			'dependentForm' => $dependentForm,
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

        $this->userLog()->log($user, 'Senha temporária gerada');

		$this->messages()->flashSuccess("A senha do usuário foi alterada com sucesso.");
		return $this->redirect()->toRoute('admin/default', [
			'controller' => 'user',
			'action' => 'update',
			'id' => $user->getId()
		]);
	}

	public function validateUserAction()
    {
        $userId = $this->params()->fromRoute($this->getIdentifierName());

        /** @var User $user */
        $user = $this->getRepository(User::class)->find($userId);
        if(!$user) {
            $this->messages()->flashError("Usuário não encontrado!");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'user',
                'action' => 'indeex'
            ]);
        }

        $user->setConfirmedRegister(true);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess("Usuário validado com sucesso.");
        $this->userLog()->log($user, 'Cadastro de usuário validado');
        return $this->redirect()->toRoute('admin/default', [
            'controller' => 'user',
            'action' => 'update',
            'id' => $user->getId()
        ]);
    }
}