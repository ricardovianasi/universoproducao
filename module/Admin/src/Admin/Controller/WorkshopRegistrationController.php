<?php
namespace Admin\Controller;

use Admin\Form\Workshop\WorkshopRegistrationForm;
use Admin\Form\Workshop\WorkshopStatusModalForm;
use Application\Entity\Form\Form;
use Application\Entity\Form\FormElements;
use Application\Entity\Programing\Programing;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\User\User;
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
        $statusModalForm = new WorkshopStatusModalForm($this->getEntityManager());

        $event = null;

        $dataAttr = $this->params()->fromQuery();
        if(!empty($dataAttr['event'])) {
            $event = $dataAttr['event'];
        } else {
            $event = $this->getDefaultEvent()?$this->getDefaultEvent()->getId():null;
        }
        $dataAttr['event'] = $event;

        $searchForm = new WorkshopRegistrationForm($this->getEntityManager(), null, $event);
        $searchForm->setData($dataAttr);
        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }
        $items = $this->search(WorkshopSubscription::class, $dataAttr);

        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm,
            'searchData' => $dataAttr,
            'isFiltered' => !empty($data) ? true : false,
            'statusModalForm' => $statusModalForm
        ]);

        return $this->getViewModel();
    }

    public function createAction($data)
    {
        $result = $this->persist($data);
        //$result->setTemplate('admin/workshop-registration/update.phtml');
        return $result;
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

        $this->messages()->flashSuccess('Inscrição excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'workshop-registration']);
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

        $user = null;
        if($this->getRequest()->isPost()) {
            if (!empty($data['user'])) {
                $user = $this
                    ->getRepository(User::class)
                    ->find($data['user']);
            }
        } elseif($workshopSubscription->getUser()) {
            $user = $workshopSubscription->getUser();
        }
        $workshopSubscription->setUser($user);

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

        $noValidate = $this->params()->fromPost('no-validate', false);
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if(!$noValidate) {
                if ($form->isValid()) {

                    $user = null;
                    if (!empty($data['user'])) {
                        $user = $this
                            ->getRepository(User::class)
                            ->find($data['user']);
                    }
                    $workshopSubscription->setUser($user);
                    unset($data['user']);

                    $registration = null;
                    if (!empty($data['registration'])) {
                        $registration = $this
                            ->getRepository(Registration::class)
                            ->find($data['registration']);
                    }
                    $workshopSubscription->setRegistration($registration);
                    $workshopSubscription->setEvent($registration->getEvent());
                    unset($data['registration']);

                    $workshop = null;
                    if (!empty($data['workshop'])) {
                        $workshop = $this
                            ->getRepository(Workshop::class)
                            ->find($data['workshop']);
                    }
                    $workshopSubscription->setWorkshop($workshop);
                    unset($data['workshop']);

                    $formAnswer = new ArrayCollection();
                    foreach ($workshopSubscription->getFormAnswers() as $fA) {
                        $this->getEntityManager()->remove($fA);
                    }
                    if (!empty($data['form_answer'])) {
                        $formOption = $reg->getOption(Options::WORKSHOP_FORM);
                        $workshopForm = $this
                            ->getEntityManager()
                            ->getRepository(Form::class)
                            ->find($formOption->getValue());

                        foreach ($data['form_answer'] as $key => $fA) {
                            $workshopAnswerForm = new WorkshopSubscriptionAnswerForm();
                            $workshopAnswerForm->setSubscription($workshopSubscription);
                            $workshopAnswerForm->setForm($workshopForm);
                            $workshopAnswerForm->setQuestion($key);
                            $workshopAnswerForm->setAnswer($fA);

                            $formAnswer->add($workshopAnswerForm);
                        }
                    }
                    $workshopSubscription->setFormAnswers($formAnswer);

                    if (!empty($data['status'])) {
                        $workshopSubscription->setStatus($data['status']);
                    }

                    $pontuations = new ArrayCollection();
                    if (!empty($data['pontuation'])) {
                        foreach ($data['pontuation'] as $key => $value) {
                            $pontuation = $this
                                ->getRepository(PontuationItems::class)
                                ->find($value);

                            $pontuations->add($pontuation);
                        }
                    }
                    $workshopSubscription->setPontuations($pontuations);

                    $this->getEntityManager()->persist($workshopSubscription);
                    $this->getEntityManager()->flush();

                    if ($id) {
                        $this->messages()->success("Inscrição atualizada com sucesso!");
                    } else {
                        $this->messages()->flashSuccess("Inscrição incluída com sucesso!");
                        return $this->redirect()->toRoute('admin/default', [
                            'controller' => 'workshop-registration',
                            'action' => 'update',
                            'id' => $workshopSubscription->getId(),
                        ]);
                    }
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

    public function exportListAction()
    {
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(WorkshopSubscription::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'workshop_subscription' ,'xlsx');
    }

    public function exportDetailsListAction()
    {
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(WorkshopSubscription::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'workshop_details' ,'docx');
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

            $answers = [];
            foreach ($obj->getFormAnswers() as $fw) {
                $answers[$fw->getQuestion()] = $fw->getAnswer();
            }

            $item = [
                'event_name' => $obj->getEvent()->getShortName(),
                'user_name' => $obj->getUser()->getName(),
                'user_identifier' => $obj->getUser()->getIdentifier(),
                'user_birth_date' => $obj->getUser()->getBirthDate()?$obj->getUser()->getBirthDate()->format('d/m/Y'):"",
                'user_parent_name' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getName() : "",
                'user_parent_identifier' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getIdentifier() : "",
                'user_email' => $obj->getUser()->getEmail(),
                'user_address' => $obj->getUser()->getFullAddress(),
                'user_phones' => $obj->getUser()->getFullPhones(),
                'workshop_name' => $obj->getWorkshop()->getName(),
                'workshop_programation' => implode(';', $workshopProgramationItems),
                'status' => Status::get($obj->getStatus()),
            ];
            $preparedItems[]['object'] = array_merge($item, $answers);
        }

        return $preparedItems;
    }

    public function statusAction()
    {
        if(!$this->getRequest()->isPost()) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'workshop-registration',
                'action' => 'index'
            ]);
        }

        $data = $this->getRequest()->getPost()->toArray();
        if(empty($data['status'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'workshop-registration',
                'action' => 'index'
            ]);
        }

        if(empty($data['filter'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'workshop-registration',
                'action' => 'index'
            ]);
        }

        $status = $data['status'];
        parse_str(urldecode($data['filter']), $filter);

        if(empty($filter['selected'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'workshop-registration',
                'action' => 'index'
            ]);
        }

        $selectedItens = [];
        if($filter['selected'] == 'all') {
            $selectedItens = $this->search(WorkshopSubscription::class, $filter, [], true);
        } else {
            $selected = explode(',', $filter['selected']);
            if(!$selected) {
                $this->messages()->flashError("Erro ao processar solicitação.");
                return $this->redirect()->toRoute('admin/default', [
                    'controller' => 'workshop-registration',
                    'action' => 'index'
                ]);
            }

            $qb = $this
                ->getRepository(WorkshopSubscription::class)
                ->createQueryBuilder('m');

            $selectedItens = $qb
                ->andWhere($qb->expr()->in('m.id', ':arrayId'))
                ->setParameter('arrayId', $selected)
                ->getQuery()
                ->getResult();
        }

        $contItensChange = 0;
        foreach ($selectedItens as $subscription) {
            if($subscription) {
                $subscription->setStatus($status);
                $this->getEntityManager()->persist($subscription);

                $contItensChange++;
            }
        }

        $this->getEntityManager()->flush();
        $this->messages()->flashSuccess("Status alterado com suscesso!");
        return $this->redirect()->toRoute('admin/default', [
            'controller' => 'workshop-registration',
            'action' => 'index',
        ], ['query'=>$filter]);

    }
}