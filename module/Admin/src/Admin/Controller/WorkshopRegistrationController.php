<?php
namespace Admin\Controller;

use Admin\Form\Programing\ProgramingForm;
use Admin\Form\Workshop\ManagerForm;
use Admin\Form\Workshop\WorkshopForm;
use Admin\Form\Workshop\WorkshopPontuationForm;
use Admin\Form\Workshop\WorkshopRegistrationForm;
use Admin\Form\Workshop\WorkshopSearchForm;
use Application\Entity\City;
use Application\Entity\Form\Form;
use Application\Entity\Programing\Programing;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Type;
use Application\Entity\User\User;
use Application\Entity\Workshop\Manager;
use Application\Entity\Workshop\PontuationItems;
use Application\Entity\Workshop\Workshop;
use Application\Entity\Workshop\WorkshopSubscription;
use Application\Entity\Workshop\WorkshopSubscriptionAnswerForm;
use Doctrine\Common\Collections\ArrayCollection;

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

		/** @var Registration $reg */
        $reg = null;
		if($this->getRequest()->isPost()) {
            $registrationID = $this->params()->fromPost('registration');
            $reg = $this->getRepository(Registration::class)->find($registrationID);
        } elseif($workshopSubscription->getRegistration()) {
		    $reg = $workshopSubscription->getRegistration();
        }

        $form = new WorkshopRegistrationForm($this->getEntityManager(), $reg);

        $pontuationItems = [];
		if($reg) {
            $pontuationOpt = $reg->getOption(Options::WORKSHOP_PONTUATION);
            if($pontuationOpt) {
                $pontuationItems = $this
                    ->getRepository(PontuationItems::class)
                    ->findBy([
                        'pontuation' => $pontuationOpt->getValue()
                    ]);
            }
        }

		if($this->getRequest()->isPost()) {
		    $data = $this->getRequest()->getPost()->toArray();
			$form->setData($data);
			if($form->isValid()) {

                $user = null;
                if (!empty($data['user'])) {
                    $user = $this
                        ->getRepository(User::class)
                        ->find($data['user']);
                }
                $workshopSubscription->setUser($user);
                unset($data['user']);

                $registration = null;
                if(!empty($data['registration'])) {
                    $registration = $this
                        ->getRepository(Registration::class)
                        ->find($data['registration']);
                }
                $workshopSubscription->setRegistration($registration);
                unset($data['registration']);

                $workshop = null;
                if(!empty($data['workshop'])) {
                    $workshop = $this
                        ->getRepository(Workshop::class)
                        ->find($data['workshop']);
                }
                $workshopSubscription->setWorkshop($workshop);
                $workshopSubscription->setEvent($workshop->getEvent());
                unset($data['workshop']);

                $formAnswer = new ArrayCollection();
                foreach ($workshopSubscription->getFormAnswers() as $fA) {
                    $this->getEntityManager()->remove($fA);
                }
                if(!empty($data['form_answer'])) {
                    $formOption = $reg->getOption(Options::WORKSHOP_FORM);
                    $workshopForm = $this
                        ->getEntityManager()
                        ->getRepository(Form::class)
                        ->find($formOption->getValue());

                    foreach ($data['form_answer'] as $key=>$fA) {
                        $workshopAnswerForm = new WorkshopSubscriptionAnswerForm();
                        $workshopAnswerForm->setSubscription($workshopSubscription);
                        $workshopAnswerForm->setForm($workshopForm);
                        $workshopAnswerForm->setQuestion($key);
                        $workshopAnswerForm->setAnswer($fA);

                        $formAnswer->add($workshopAnswerForm);
                    }
                }
                $workshopSubscription->setFormAnswers($formAnswer);

                if(!empty($data['status'])) {
                    $workshopSubscription->setStatus($data['status']);
                }

                $pontuations = new ArrayCollection();
                if(!empty($data['pontuation'])) {
                    foreach ($data['pontuation'] as $key=>$value) {
                        $pontuation = $this
                            ->getRepository(PontuationItems::class)
                            ->find($value);

                        $pontuations->add($pontuation);
                    }
                }
                $workshopSubscription->setPontuations($pontuations);

                $this->getEntityManager()->persist($workshopSubscription);
                $this->getEntityManager()->flush();

                if($id) {
                    $this->messages()->success("Inscrição atualizada com sucesso!");
                } else {
                    $this->messages()->flashSuccess("Inscrição incluída com sucesso!");
                    return $this->redirect()->toRoute('admin/default', [
                        'controller' => 'workshop-registration',
                        'action' => 'update',
                        'id' => $id,
                    ]);
                }
			}
		} else {
			$form->setData($workshopSubscription->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'pontuationItems' => $pontuationItems,
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