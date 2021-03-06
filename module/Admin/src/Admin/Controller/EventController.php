<?php
namespace Admin\Controller;

use Admin\Form\Event\EventForm;
use Admin\Form\Event\EventSearchForm;
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;
use Application\Entity\Event\SubEvent;
use Doctrine\Common\Collections\ArrayCollection;

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
		$form = new EventForm($this->getEntityManager());

		if($id) {
			$event = $this->getRepository(Event::class)->find($id);
		} else {
			$event = new Event();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {

			    $places = new ArrayCollection();
			    if(!empty($data['places'])) {
			        foreach ($data['places'] as $placeId) {
			            $place = $this->getRepository(Place::class)->find($placeId);
			            if($place)
			                $places->add($place);
                    }
                }
                $data['places'] = $places;

                $subEvents = new ArrayCollection();
                if(!empty($data['sub_events'])) {
                    foreach ($data['sub_events'] as $subId) {
                        $subEvent = $this->getRepository(SubEvent::class)->find($subId);
                        if($subEvent)
                            $subEvents->add($subEvent);
                    }
                }
                $data['sub_events'] = $subEvents;

				$event->setData($data);
				$this->getEntityManager()->persist($event);
				$this->getEntityManager()->flush();

				if($event->getDefault()) {
				    //Remover o evento default de outros registros
                    $qb = $this->getEntityManager()->createQueryBuilder();
                    $qb->update(Event::class, 'e')
                        ->set('e.default', 0)
                        ->andWhere('e.id != :id')
                        ->setParameter('id', $event->getId())
                        ->getQuery()->execute();
                }

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