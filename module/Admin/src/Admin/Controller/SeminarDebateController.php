<?php
namespace Admin\Controller;

use Admin\Form\Seminar\SeminarDebateForm;
use Admin\Form\Seminar\SeminarDebateProgramingForm;
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Seminar\Category;
use Application\Entity\Seminar\Debate;
use Application\Entity\Seminar\Thematic;

class SeminarDebateController extends AbstractAdminController
    implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new SeminarDebateForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$items = $this->search(Debate::class, $data);

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
		$result->setTemplate('admin/seminar-debate/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$art = $this->getRepository(Debate::class)->find($id);
		$this->getEntityManager()->remove($art);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Debate excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'seminar-debate']);
	}

	public function persist($data, $id = null)
	{
		if($id) {
			$debate = $this->getRepository(Debate::class)->find($id);
		} else {
			$debate = new Debate();
		}

		$programingForm = new SeminarDebateProgramingForm($this->getEntityManager());
        $form = new SeminarDebateForm($this->getEntityManager());
		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {

			    $event = null;
			    if(!empty($data['event'])) {
			        $event = $this->getRepository(Event::class)->find($data['event']);
                }
                $debate->setEvent($event);
			    unset($data['event']);

                $thematic = null;
                if(!empty($data['thematic'])) {
                    $thematic = $this->getRepository(Thematic::class)->find($data['thematic']);
                }
                $debate->setThematic($thematic);
                unset($data['thematic']);

                $category = null;
                if(!empty($data['category'])) {
                    $category = $this->getRepository(Category::class)->find($data['category']);
                }
                $debate->setCategory($category);
                unset($data['category']);

                $programing = [];
                if(!empty($data['programing'])) {
                    $programing = $data['programing'];
                }
                unset($data['programing']);
                foreach ($debate->getPrograming() as $p) {
                    $this->getEntityManager()->remove($p);
                }

                $debate->setData($data);
                $this->getEntityManager()->persist($debate);
                $this->getEntityManager()->flush();

                //Persiste a grade da programação
                foreach ($programing as $pro) {
                    $debateProg = new Programing();
                    $debateProg->setEvent($event);
                    $debateProg->setType(Type::SEMINAR_DEBATE);
                    $debateProg->setObjectId($debate->getId());

                    if(!empty($pro['place'])) {
                        $place = $this
                            ->getRepository(Place::class)
                            ->find($pro['place']);

                        $pro['place'] = $place;
                    }

                    if(!empty($pro['available_places'])) {
                        $pro['available_places'] = (int) $pro['available_places'];
                    } else {
                        unset($pro['available_places']);
                    }

                    $debateProg->setData($pro);
                    $this->getEntityManager()->persist($debateProg);
                }

                $this->getEntityManager()->flush();
                $this->getEntityManager()->refresh($debate);

				if($id) {
					$this->messages()->success("Debate atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Debate criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'seminar-debate',
						'action' => 'update',
						'id' => $debate->getId()
					]);
				}
			}
		} else {
			$form->setData($debate->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'programingForm' => $programingForm,
			'debate' => $debate
		]);
	}
}