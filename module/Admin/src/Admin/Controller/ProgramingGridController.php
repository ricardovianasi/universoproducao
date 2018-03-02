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

class ProgramingGridController extends AbstractAdminController
    implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new ProgramingForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $qb = $this
            ->getRepository(Programing::class)
            ->createQueryBuilder('p')
            ->andWhere('p.parent is NULL')
            ->addOrderBy('p.date', 'ASC')
            ->addOrderBy('p.order', 'ASC')
            ->addOrderBy('p.startTime', 'ASC');

        if(!empty($data['event'])) {
            $qb->andWhere('p.event = :idEvent')->setParameter('idEvent', $data['event']);
        }

		$items = $qb->getQuery()->getResult();

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
		$programing = $this->getRepository(Programing::class)->find($id);
		$this->getEntityManager()->remove($programing);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Item excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'programing-grid']);
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

	public function orderAction()
    {
	    if($this->getRequest()->isPost()) {
	        $data = $this->getRequest()->getPost()->toArray();
	        $order = 0;
	        $sort = explode(';', $data['sort']);
	        foreach ($sort as $id) {
                $prog = $this->getRepository(Programing::class)->find($id);
                if($prog) {
                    $prog->setOrder($order++);
                    $this->getEntityManager()->persist($prog);
                }
            }
            $this->getEntityManager()->flush();
            $this->messages()->flashSuccess('Ordenação realizada com sucesso.');
        }

        return $this->redirect()->toRoute('admin/default', ['controller'=>'programing-grid']);
    }

}