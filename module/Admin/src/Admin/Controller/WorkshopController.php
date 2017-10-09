<?php
namespace Admin\Controller;

use Admin\Form\Workshop\ManagerForm;
use Admin\Form\Workshop\WorkshopForm;
use Admin\Form\Workshop\WorkshopSearchForm;
use Application\Entity\City;
use Application\Entity\Workshop\Manager;
use Application\Entity\Workshop\Workshop;

class WorkshopController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new WorkshopSearchForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$items = $this->search(Workshop::class, $data);

		$this->getViewModel()->setVariables([
			'items' => $items,
            'searchForm' => $searchForm
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
		$result->setTemplate('admin/workshop/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$workshop = $this->getRepository(Workshop::class)->find($id);
		$this->getEntityManager()->remove($workshop);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Oficina excluída com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'workshop']);
	}

	public function persist($data, $id = null)
	{
		$form = new WorkshopForm($this->getEntityManager());

		if($id) {
			$workshop = $this->getRepository(Manager::class)->find($id);
		} else {
			$workshop = new Manager();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$workshop->setData($data);

				$this->getEntityManager()->persist($workshop);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Oficina atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Oficina criada com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'workshop',
						'action' => 'update',
						'id' => $workshop->getId()
					]);
				}
			}
		} else {
			$form->setData($workshop->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'workshop' => $workshop
		]);
	}


}