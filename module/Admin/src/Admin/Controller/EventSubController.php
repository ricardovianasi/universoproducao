<?php
namespace Admin\Controller;

use Admin\Form\Event\PlaceForm;
use Admin\Form\Event\SubEventForm;
use Application\Entity\City;
use Application\Entity\Event\Place;
use Application\Entity\Event\SubEvent;

class EventSubController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new SubEventForm();
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$subEvents = $this->search(SubEvent::class, $data);

		$this->getViewModel()->setVariables([
			'subEvents' => $subEvents,
            'searchForm' => $searchForm,
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
		$result->setTemplate('admin/event-sub/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$subEvent = $this->getRepository(SubEvent::class)->find($id);
		$this->getEntityManager()->remove($subEvent);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Sub-mostra excluída com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'event-sub']);
	}

	public function persist($data, $id = null)
	{
		$form = new SubEventForm();

		if($id) {
			$subEvent = $this->getRepository(SubEvent::class)->find($id);
		} else {
			$subEvent = new SubEvent();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$subEvent->setData($data);
				$this->getEntityManager()->persist($subEvent);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Sub-mostra atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Sub-mostra criada com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'event-sub',
						'action' => 'update',
						'id' => $subEvent->getId()
					]);
				}
			}
		} else {
			$form->setData($subEvent->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'subEvent' => $subEvent
		]);
	}


}