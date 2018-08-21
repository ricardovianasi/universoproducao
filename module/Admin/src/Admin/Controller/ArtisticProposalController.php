<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 20/02/2016
 * Time: 11:35
 */

namespace Admin\Controller;

use Admin\Form\Proposal\ArtisticProposalForm;
use Admin\Form\User\ResetPasswordForm;
use Admin\Form\User\UserForm;
use Admin\Form\User\UserSearch;
use Application\Entity\AdminUser\User;
use Application\Entity\Proposal\ArtisticProposal;
use Util\Security\Crypt;

class ArtisticProposalController extends AbstractAdminController
	implements CrudInterface

{
	public function indexAction()
	{
		$searchForm = new ArtisticProposalForm();
		$dataAttr = $this->params()->fromQuery();
		$searchForm->setData($dataAttr);
		$searchForm->isValid();

		$items = $this->search(ArtisticProposal::class, $searchForm->getData());

		$this->getViewModel()->setVariables([
			'searchForm' => $searchForm,
			'items' => $items
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
        $result->setTemplate('admin/artistic-proposal/create.phtml');
        return $result;
	}

	public function deleteAction($id)
	{
		$proposal = $this->getRepository(ArtisticProposal::class)->find($id);

		$this->getEntityManager()->remove($proposal);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Proposta excluída com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'artistic-proposal']);
	}

	public function persist($data, $id = null)
	{
		$form = new ArtisticProposalForm($this->getEntityManager());
		if($id) {
			$proposal = $this->getRepository(ArtisticProposal::class)->find($id);
		} else {
            $proposal = new ArtisticProposal();
        }

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$validData = $form->getData();

				$proposal->setData($validData);

				$this->getEntityManager()->persist($proposal);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Proposta atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Proposta criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'artistic-proposal',
						'action' => 'update',
						'id' => $proposal->getId()
					]);
				}
			}
		} else {
			$form->setData($proposal->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'proposal' => $proposal
		]);
	}
}