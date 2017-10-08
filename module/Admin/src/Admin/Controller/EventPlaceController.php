<?php
namespace Admin\Controller;

use Admin\Form\Event\EventForm;
use Admin\Form\Event\PlaceForm;
use Application\Entity\City;
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;

class EventPlaceController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new PlaceForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$places = $this->search(Place::class, $data);

		$this->getViewModel()->setVariables([
			'places' => $places,
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
		$result->setTemplate('admin/event-place/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$place = $this->getRepository(Place::class)->find($id);
		$this->getEntityManager()->remove($place);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Local excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'event-place']);
	}

	public function persist($data, $id = null)
	{
		$form = new PlaceForm($this->getEntityManager());

		if($id) {
			$place = $this->getRepository(Place::class)->find($id);
		} else {
			$place = new Place();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {

                if(isset($data['city'])) {
                    $city = $this
                        ->getRepository(City::Class)
                        ->find($data['city']);

                    $data['city'] = $city;
                }

				$place->setData($data);
				$this->getEntityManager()->persist($place);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Local atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Local criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'event-place',
						'action' => 'update',
						'id' => $place->getId()
					]);
				}
			}
		} else {
			$form->setData($place->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'place' => $place
		]);
	}


}