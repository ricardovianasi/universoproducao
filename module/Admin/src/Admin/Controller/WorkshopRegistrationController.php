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
        $workshop = $this->getRepository(WorkshopSubscription::class)->find($id);
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

            $workshopProgramationItems = '';

            $workshopProgramation = $this->getRepository(Programing::class)->findBy([
                'event' => $obj->getEvent()->getId(),
                'type' => Type::WORKSHOP,
                'objectId' => $obj->getWorkshop()->getId()
            ]);
            if(count($workshopProgramation)) {
                $prog_first = reset($workshopProgramation);
                $prog_last = end($workshopProgramation);

                if($prog_first->getDate()
                    && $prog_first->getDate() instanceof \DateTime
                    && $prog_last->getDate()
                    && $prog_last->getDate() instanceof \DateTime) {

                    $workshopProgramationItems =
                        $prog_first->getDate()->format('d')
                        . ' a '
                        . $prog_last->getDate()->format('d/m')
                        . ', de '
                        . $prog_first->getStartTime()->format('H:i\h')
                        . ' às '
                        . $prog_last->getEndTime()->format('H:i\h');
                }
            }


            switch ( $obj->getWorkshop()->getId() ) {
                case 59:
                    $workshopProgramationItems = "30 out à 1º nov | 10 às 12h e 14 às 16hs";
                    break;
                case 60:
                    $workshopProgramationItems = "29 out à 1 nov | 10 às 12h (quinta a sábado) e 10 às 13h (domingo)";
                    break;
                case 61:
                    $workshopProgramationItems = "29 out à 1 nov | 15h às 17h (quinta a sábado) e 10 às 13h (domingo)";
                    break;
                case 62:
                    $workshopProgramationItems = "30 out à 1 nov | 14 às 16h (sexta), 10 às 12h e 14 às 16h (sábado) e 10 às 13h (domingo)";
                    break;
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
                'workshop_programation' => $workshopProgramationItems,
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

    public function comunicadosAction()
    {
        $this->getViewModel()->setTerminal(true);

        $items = $this
            ->getRepository(WorkshopSubscription::class)
            ->createQueryBuilder('m')
            ->andWhere('m.status = :status')
            ->andWhere('m.event = :idEvent')
            ->andWhere('m.workshop IN (:workshops)')
            ->setParameters([
                'status' => 'selected',
                'idEvent' => 1092,
                'workshops' => array(43, 44)
            ])
            ->getQuery()
            ->getResult();

        //var_dump(count($items)); exit();
        $count = 0;
        foreach ($items as $item) {
            /** @var WorkshopSubscription $item */
            //$item = new WorkshopSubscription();

            $msg = "<p>Olá ".$item->getUser()->getName().",</p>";

            $msg.= "<p>Agradecemos seu interesse em participar da 13ª CineBH e 10º Brasil CineMundi. 
			Informamos que recebemos sua inscrição para participar do ".$item->getWorkshop()->getName()."</p>";

            $msg.="<p><strong>ATENÇÃO:</strong></p>";
            $msg.="<p>Você deverá apresentar documento original com foto e o comprovante de inscrição (anexo/ disponível para download) impresso e assinado para retirar sua credencial no dia da atividade, no balcão de credenciamento localizado no Jardim Externo do Palácio das Artes (piso inferior).</p>";
            $msg.="<p>É necessário <strong>CONFIRMAR SUA PRESENÇA até 18h (horário de Brasília) do dia 12/09</strong>, entrando no seu Menu do Usuário (<a href='http://meu-universo.universoproducao.com.br/autenticacao'>http://meu-universo.universoproducao.com.br/autenticacao</a>) com o email e senha cadastrada. </p>";
            $msg.="<p>- <strong>NÃO RESPONDA ESSE EMAIL</strong>. Qualquer dúvida ou problema entre em contato através do email oficinas@universoproducao.com.br ou pelo telefone: (31) 3282-2366.</p>";
            $msg.="<p>Atenciosamente, <br />
			Programa de Formação <br />
			13ª CineBH e 10º Brasil CineMundi</p>";


            /*$workshopProgramation = $this->getRepository(Programing::class)->findBy([
                'event' => $item->getEvent()->getId(),
                'type' => Type::WORKSHOP,
                'objectId' => $item->getWorkshop()->getId()
            ]);
            $workshopProgramationItems = [];
            foreach ($workshopProgramation as $pro) {
                $desc = $pro->getDate()->format('d/m/Y')
                    . ' | ' . $pro->getStartTime()->format('H:i')
                    . ' às '
                    . $pro->getEndTime()->format('H:i');
                $workshopProgramationItems[] = $desc;
            }

            $msg.="<p>Data e hora de realização:<br />";
            $msg.=implode('<br />', $workshopProgramationItems);
            $msg.="<br />Local para credenciamento: Centro Cultural SESIMINAS Yves Alves
            <br />Rua Direita, 168 – Tiradentes - MG</p>";

            $msg.="<p><strong>Atenção:
            <br />- Prazo de confirmação: até às 20 horas (horário de Brasília), do dia 01/06/2019 - sábado.
            <br />- Caso não confirme sua presença no prazo estipulado sua inscrição será considerada como DESISTÊNCIA.</strong></p>";

            $confirmacao  = $this->url()->fromRoute('meu-universo/workshop', array(
                'controller' => 'workshop-registration',
                'action' => 'confirmacao',
                'id_reg' => $item->getRegistration()->getHash(),
                'id' => $item->getWorkshop()->getId()
            ));

            $msg.= "<p>Para confirmar ou não sua participação, clique em uma das opções abaixo:</p>";
            $msg.= "<p><ul>
                <li><a href='".$confirmacao.'?confirmacao=sim'."'>Confirmo minha participação / Imprimir Documento de Inscrição de selecionado >> </a></li>
                <li><a href='".$confirmacao.'?confirmacao=nao'."'> Não confirmo minha participação >> </a></li>
            </ul></p>";

            $msg.= "<p><strong>Observação</strong>: Caso não consiga acessar os links acima, siga o procedimento abaixo:</p>";
            $msg.= "<ul>
                <li>1) Acesse: <a href='www.universoproducao.com.br'>www.universoproducao.com.br</a> </li>
                <li>2) Clique em Menu do Usuário (Ícone no lado superior direito do Menu principal)</li>
                <li>3) Informe seu email e senha cadastrada)</li>
                <li>4) Clique em Minhas Inscrições (Ícone no lado superior direito do Menu principal)</li>
                <li>5) Clique em Confirmar Presença ou Não Confirmar Presença</li>
                <li>6) Qualquer dúvida entre em contato: oficinas@universoproducao.com.br </li>
            </ul>";

            $msg.= "<p><strong>Apresente o comprovante de confirmação impresso</strong> e um documento com foto para retirar a credencial e o material na secretaria do evento no dia de início da oficina no seguinte endereço:
			<br /><br />Centro de Artes e Convenções<br />Diogo de Vasconcelos, 328 - Pilar, Ouro Preto - MG</p>";

            $msg.= "<p>Convidamos você para participar também das outras atividades do evento.</p>";
            $msg.= "<p>A programação é gratuita e pode ser conferida no site <a href='www.cineop.com.br'>www.cineop.com.br</a>.</p>";*/

            //$to[$item->getAuthor()->getName()] = 'ricardovianasi@gmail.com';
            /** @var \SendGrid\Response $return */
            $return = $this->mailService()->simpleSendEmail(
                [$item->getUser()->getName()=>$item->getUser()->getEmail()],
                //[$item->getUser()->getName()=>'ricardovianasi@gmail.com'],
                'Programa de Formação - 13ª CineBH e 10º Brasil CineMundi', $msg);

            $count++;
            echo "$count - Nome: " . $item->getUser()->getName();
            echo "<br />Email: " . $item->getUser()->getEmail();
            echo "<br />Oficina: " . $item->getWorkshop()->getName();
            if($return->statusCode() == 202) {
                echo "<br /><b>******************-SUCESSO-******************</b><br /><br />";
            } else {
                echo "<b>******************-ERRO-******************</b><br /><br />";
            }

            //break;
            //exit();
        }

        return $this->getViewModel();
    }

    public function comunicadosNaoSelecionadosAction()
    {
        $this->getViewModel()->setTerminal(true);

        $items = $this
            ->getRepository(WorkshopSubscription::class)
            ->createQueryBuilder('m')
            ->andWhere('m.status = :status')
            ->andWhere('m.event = :idEvent')
            ->setParameters([
                'status' => 'not_selected',
                'idEvent' => 1092
            ])
            ->getQuery()
            ->getResult();

        /*var_dump(count($items)); exit();*/
        $count = 0;
        foreach ($items as $item) {
            /** @var WorkshopSubscription $item */
            //$item = new WorkshopSubscription();

            $msg = "<p>Prezado(a) ".$item->getUser()->getName().",</p>";

            $msg.= "<p>Agradecemos seu interesse em participar da 13ª CineBH e 10º Brasil CineMundi. 
			<br /><br />Informamos que você não foi selecionado(a) para a atividade ".$item->getWorkshop()->getName().".</p>";

            $msg.= "<p>Convidamos você para participar das outras atividades do evento: sessões de filmes, debates, rodas de conversa e atrações artísticas.</p>";
            $msg.= "<p>A programação é gratuita e, está disponível no site <a href='www.cinebh.com.br'>www.cinebh.com.br</a>.</p>";
            $msg.= "<p>Atenciosamente,<br />Programa de Formação - 13ª CineBH e 10º Brasil CineMundi</p>";

            //$to[$item->getAuthor()->getName()] = 'ricardovianasi@gmail.com';
            /** @var \SendGrid\Response $return */
            $return = $this->mailService()->simpleSendEmail(
                [$item->getUser()->getName()=>$item->getUser()->getEmail()],
                //[$item->getUser()->getName()=>'ricardovianasi@gmail.com'],
                'Programa de Formação - 13ª CineBH e 10º Brasil CineMundi', $msg);

            $count++;
            echo "$count - Nome: " . $item->getUser()->getName();
            echo "<br />Email: " . $item->getUser()->getEmail();
            echo "<br />Oficina: " . $item->getWorkshop()->getName();
            if($return->statusCode() == 202) {
                echo "<br /><b>******************-SUCESSO-******************</b><br /><br />";
            } else {
                echo "<b>******************-ERRO-******************</b><br /><br />";
            }

            //break;
            //exit();
        }

        return $this->getViewModel();
    }
}