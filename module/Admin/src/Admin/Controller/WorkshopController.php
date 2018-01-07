<?php
namespace Admin\Controller;

use Admin\Form\Workshop\WorkshopForm;
use Admin\Form\Workshop\WorkshopProgramingForm;
use Admin\Form\Workshop\WorkshopSearchForm;
use Application\Entity\Event\Place;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Registration\Registration;
use Application\Entity\Workshop\Manager;
use Application\Entity\Workshop\Workshop;

class WorkshopController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new WorkshopSearchForm($this->getEntityManager(), $this->getDefaultEvent());
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
		//$result->setTemplate('admin/workshop/create.phtml');
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
		if($id) {
			$workshop = $this->getRepository(Workshop::class)->find($id);
		} else {
			$workshop = new Workshop();
		}

        $form = new WorkshopForm($this->getEntityManager());
        $programingForm = new WorkshopProgramingForm($this->getEntityManager());

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {

				$manager = null;
				if(!empty($data['manager'])) {
				    $manager = $this->getRepository(Manager::class)->find($data['manager']);
                }
                $workshop->setManager($manager);
				unset($data['manager']);

                $registration = null;
                if(!empty($data['registration'])) {
                    $registration = $this->getRepository(Registration::class)->find($data['registration']);
                }
                $workshop->setRegistration($registration);
                unset($data['registration']);

                $programing = [];
                if(!empty($data['programing'])) {
                    $programing = $data['programing'];
                }
                unset($data['programing']);
                foreach ($workshop->getPrograming() as $p) {
                    $this->getEntityManager()->remove($p);
                }

                $workshop->setData($data);

				$this->getEntityManager()->persist($workshop);
				$this->getEntityManager()->flush();

                //Persiste a grade da programação
                foreach ($programing as $pro) {
                    $artProg = new Programing();
                    $artProg->setEvent($workshop->getEvent());
                    $artProg->setType(Type::WORKSHOP);
                    $artProg->setObjectId($workshop->getId());

                    if(!empty($pro['place'])) {
                        $place = $this
                            ->getRepository(Place::class)
                            ->find($pro['place']);

                        $pro['place'] = $place;
                    }

                    $artProg->setData($pro);
                    $this->getEntityManager()->persist($artProg);
                }

                $this->getEntityManager()->flush();
                $this->getEntityManager()->refresh($workshop);

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
			'workshop' => $workshop,
            'programingForm' => $programingForm
		]);
	}
}