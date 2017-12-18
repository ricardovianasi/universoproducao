<?php
namespace Admin\Controller;

use Admin\Form\Programing\ProgramingForm;
use Admin\Form\Workshop\ManagerForm;
use Admin\Form\Workshop\WorkshopForm;
use Admin\Form\Workshop\WorkshopRegistrationForm;
use Admin\Form\Workshop\WorkshopSearchForm;
use Application\Entity\City;
use Application\Entity\Programing\Programing;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Type;
use Application\Entity\Workshop\Manager;
use Application\Entity\Workshop\Workshop;
use Application\Entity\Workshop\WorkshopSubscription;

class WorkshopRegistrationController extends AbstractAdminController
    implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new WorkshopRegistrationForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$items = $this->search(WorkshopSubscription::class, $data);

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
		//$result->setTemplate('admin/workshop-registration/create.phtml');
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
			$workshopSubscription = $this->getRepository(WorkshopSubscription::class)->find($id);
		} else {
			$workshopSubscription = new WorkshopSubscription();
		}

        $form = new WorkshopRegistrationForm($this->getEntityManager());

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
			}
		} else {
			$form->setData($workshopSubscription->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'workshopSubscription' => $workshopSubscription,
		]);
	}

	public function exportConfirmationAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $id = $this->params()->fromRoute('id');
        $item = $this->getRepository(WorkshopSubscription::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);

        return $this->prepareReport($preparedItems, 'workshop-confirmation' ,'pdf');
    }

    protected function prepareItemsForReports($items)
    {
        if(!is_array($items)) {
            $items = [$items];
        }

        $preparedItems = [];
        foreach ($items as $obj) {
            $workshopProgramation = $this->getRepository(Programing::class)->findBy([
                'event' => $obj->getEvent()->getId(),
                'type' => Type::WORKSHOP,
                'objectId' => $obj->getWorkshop()->getId()
            ]);
            $workshopProgramationItems = [];
            foreach ($workshopProgramation as $pro) {
                $desc = $pro->getDate()->format('d/m/Y')
                    . ' | ' . $pro->getStartTime()->format('H:i')
                    . ' às '
                    . $pro->getEndTime()->format('H:i');
                $workshopProgramationItems[] = $desc;
            }

            $preparedItems[]['workshop'] = [
                'event_name' => $obj->getEvent()->getShortName(),
                'user_name' => $obj->getUser()->getName(),
                'user_identifier' => $obj->getUser()->getIdentifier(),
                'user_birth_date' => $obj->getUser()->getBirthDate()->format('d/m/Y'),
                'user_parent_name' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getName() : "",
                'user_parent_identifier' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getIdentifier() : "",
                'workshop_name' => $obj->getWorkshop()->getName(),
                'workshop_programation' => implode(';', $workshopProgramationItems)

            ];
        }

        return $preparedItems;
    }
}