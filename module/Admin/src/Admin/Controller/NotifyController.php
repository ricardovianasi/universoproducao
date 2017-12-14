<?php
namespace Admin\Controller;

use Admin\Form\Event\EventForm;
use Admin\Form\Event\EventSearchForm;
use Admin\Form\Notify\NotifyForm;
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;
use Application\Entity\Event\SubEvent;
use Application\Entity\Notify\Notify;
use Doctrine\Common\Collections\ArrayCollection;

class NotifyController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new NotifyForm();
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);
        $searchForm->isValid();

        $data = $searchForm->getData();
        $items = $this->search(Notify::class, $data);
        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm,
            'searchData' => $dataAttr
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
		$result->setTemplate('admin/notify/create.phtml');
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
        $form = new NotifyForm();

        if($id) {
            $notify = $this->getRepository(Notify::class)->find($id);
        } else {
            $notify = new Notify();
        }

        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if ($form->isValid()) {

            }
        }

        $form->setData($notify->toArray());

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'notify' => $notify
        ]);

    }


}