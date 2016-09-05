<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 20/02/2016
 * Time: 11:35
 */

namespace Admin\Controller;

use Admin\Form\User\ProfileForm;
use Application\Entity\User\Action;
use Application\Entity\User\Controller;
use Application\Entity\User\Profile;
use Doctrine\Common\Collections\ArrayCollection;

class ProfileController extends AbstractAdminController
	implements CrudInterface

{
	public function indexAction()
	{
		$profiles = $this->search(Profile::class, []);

		$this->getViewModel()->setVariables([
			'profiles' => $profiles
		]);

		return $this->getViewModel();
	}

	public function createAction($data)
	{
		return $this->persist($data);
	}

	public function updateAction($id, $data)
	{
		$result = $this->persist($data, $id);
		$result->setTemplate('admin/profile/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		return [];
	}

	public function persist($data, $id = null)
	{
		$form = new ProfileForm();
		$modules = $this->getRepository(Controller::class)->findAll();

		if($id) {
			$profile = $this->getRepository(Profile::class)->find($id);
		} else {
			$profile = new Profile();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$profile->setData($this->prepareDataPost(Post::class, $data));

				$actionsColl = new ArrayCollection();
				if(!empty($data['actions'])) {
					foreach($data['actions'] as $actionId) {
						$action = $this->getRepository(Action::class)->find($actionId);
						$actionsColl->add($action);
					}
				}
				$profile->setActions($actionsColl);

				$this->getEntityManager()->persist($profile);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Perfil atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Perfil criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'profile',
						'action' => 'update',
						'id' => $profile->getId()
					]);
				}
			}
		}

		$form->setData($profile->toArray());

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'profile' => $profile,
			'modules' => $modules
		]);
	}
}