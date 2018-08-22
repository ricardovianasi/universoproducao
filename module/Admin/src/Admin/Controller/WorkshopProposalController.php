<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 20/02/2016
 * Time: 11:35
 */

namespace Admin\Controller;

use Admin\Form\Proposal\WorkshopProposalForm;
use Application\Entity\Proposal\WorkshopProposal;

class WorkshopProposalController extends AbstractAdminController
	implements CrudInterface

{
	public function indexAction()
	{
		$searchForm = new WorkshopProposalForm();
		$dataAttr = $this->params()->fromQuery();
		$searchForm->setData($dataAttr);
		$searchForm->isValid();

		$items = $this->search(WorkshopProposal::class, $searchForm->getData());

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
        $result->setTemplate('admin/workshop-proposal/create.phtml');
        return $result;
	}

	public function deleteAction($id)
	{
		$proposal = $this->getRepository(WorkshopProposal::class)->find($id);

		$this->getEntityManager()->remove($proposal);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Proposta excluída com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'workshop-proposal']);
	}

	public function persist($data, $id = null)
	{
		$form = new WorkshopProposalForm($this->getEntityManager());
		if($id) {
			$proposal = $this->getRepository(WorkshopProposal::class)->find($id);
		} else {
            $proposal = new WorkshopProposal();
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
						'controller' => 'workshop-proposal',
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