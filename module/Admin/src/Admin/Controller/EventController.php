<?php
namespace Admin\Controller;


use Admin\Form\Event\EventForm;
use Admin\Form\Event\EventSearchForm;
use Application\Entity\Event\Event;

class EventController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new EventSearchForm();
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$events = $this->search(Event::class, $data);

		$this->getViewModel()->setVariables([
			'events' => $events,
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
		$result->setTemplate('admin/event/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$event = $this->getRepository(Event::class)->find($id);
		$this->getEntityManager()->remove($event);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Mostra excluída com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'event']);
	}

	public function persist($data, $id = null)
	{
		$form = new EventForm();

		if($id) {
			$event = $this->getRepository(Event::class)->find($id);
		} else {
			$event = new Event();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$event->setData($data);

				$this->getEntityManager()->persist($event);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Mostra atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Mostra criada com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'event',
						'action' => 'update',
						'id' => $event->getId()
					]);
				}
			}
		} else {
			$form->setData($event->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'event' => $event
		]);
	}


}