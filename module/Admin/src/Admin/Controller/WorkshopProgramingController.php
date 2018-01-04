<?php
namespace Admin\Controller;

use Admin\Form\Workshop\WorkshopProgramingForm;
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type as ProgramingType;
use Application\Entity\Workshop\Workshop;

class WorkshopProgramingController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new WorkshopProgramingForm($this->getEntityManager(), $this->getDefaultEvent());
	    if($this->getDefaultEvent()) {
	        $searchForm->setData(['event'=>$this->getDefaultEvent()->getId()]);
        }

        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();
        $data['type'] = 'workshop';

		$items = $this->search(Programing::class, $data);

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
		$result->setTemplate('admin/workshop-programing/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$programing = $this->getRepository(Programing::class)->find($id);
		$this->getEntityManager()->remove($programing);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Horário excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'workshop-programing']);
	}

	public function persist($data, $id = null)
	{
		if($id) {
			$programing = $this->getRepository(Programing::class)->find($id);
		} else {
            $programing = new Programing();
            $programing->setType(ProgramingType::WORKSHOP);
		}

        $form = new WorkshopProgramingForm($this->getEntityManager(), $this->getDefaultEvent());

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {

			    $event = null;
			    if(!empty($data['event'])) {
			        $event = $this->getRepository(Event::class)->find($data['event']);
                }
                $programing->setEvent($event);
			    unset($data['event']);

                $programing->setObjectId($data['workshop']);

                $place = null;
                if(!empty($data['place'])) {
                    $place = $this->getRepository(Place::class)->find($data['place']);
                }
                $programing->setPlace($place);
                unset($data['place']);

                $programing->setData($data);

				$this->getEntityManager()->persist($programing);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Horário atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Horário criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'workshop-programing',
						'action' => 'update',
						'id' => $programing->getId()
					]);
				}
			}
		} else {
		    $data = $programing->toArray();
		    if(!empty($data['event'])) {
                $data['event'] = $this->getDefaultEvent()->getId();
            }
			$form->setData($data);
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'programing' => $programing
		]);
	}
}