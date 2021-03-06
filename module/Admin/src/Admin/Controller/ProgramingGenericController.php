<?php
namespace Admin\Controller;

use Admin\Form\Programing\GenericItemForm;
use Admin\Form\Programing\GenericProgramingForm;
use Admin\Form\Programing\ProgramingForm;
use Admin\Form\Seminar\SeminarDebateForm;
use Admin\Form\Seminar\SeminarDebateProgramingForm;
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;
use Application\Entity\Programing\Generic;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Seminar\Debate;
use Application\Entity\Seminar\Thematic;

class ProgramingGenericController extends AbstractAdminController
    implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new GenericItemForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$items = $this->search(Generic::class, $data);

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
		$result->setTemplate('admin/programing-generic/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$generic = $this->getRepository(Generic::class)->find($id);
		$this->getEntityManager()->remove($generic);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Item excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'programing-generic']);
	}

	public function persist($data, $id = null)
	{
		if($id) {
			$generic = $this->getRepository(Generic::class)->find($id);
		} else {
			$generic = new Generic();
		}

		$programingForm = new GenericProgramingForm($this->getEntityManager());
        $form = new GenericItemForm($this->getEntityManager());
		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {

			    $event = null;
			    if(!empty($data['event'])) {
			        $event = $this->getRepository(Event::class)->find($data['event']);
                }
                $generic->setEvent($event);
			    unset($data['event']);

                $programing = [];
                if(!empty($data['programing'])) {
                    $programing = $data['programing'];
                }
                unset($data['programing']);
                foreach ($generic->getPrograming() as $p) {
                    $this->getEntityManager()->remove($p);
                }

                $generic->setData($data);
                $this->getEntityManager()->persist($generic);
                $this->getEntityManager()->flush();

                //Persiste a grade da programação
                foreach ($programing as $pro) {
                    $genericProg = new Programing();
                    $genericProg->setEvent($event);
                    $genericProg->setType($generic->getType());
                    $genericProg->setObjectId($generic->getId());

                    if(!empty($pro['place'])) {
                        $place = $this
                            ->getRepository(Place::class)
                            ->find($pro['place']);

                        $pro['place'] = $place;
                    }

                    $genericProg->setData($pro);
                    $this->getEntityManager()->persist($genericProg);
                }

                $this->getEntityManager()->flush();
                $this->getEntityManager()->refresh($generic);

				if($id) {
					$this->messages()->success("Item atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Item criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'programing-generic',
						'action' => 'update',
						'id' => $generic->getId()
					]);
				}
			}
		} else {
			$form->setData($generic->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'programingForm' => $programingForm,
			'generic' => $generic
		]);
	}
}