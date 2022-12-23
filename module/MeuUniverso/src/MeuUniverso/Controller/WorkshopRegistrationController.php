<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use Admin\Form\Workshop\WorkshopRegistration;
use Application\Entity\Form\Form as EntityForm;
use Application\Entity\Programing\Programing;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\User\User;
use Application\Entity\Workshop\Workshop;
use Application\Entity\Workshop\WorkshopSubscription;
use Application\Entity\Workshop\WorkshopSubscriptionAnswerForm;
use Doctrine\Common\Collections\ArrayCollection;
use MeuUniverso\Form\WorkshopForm;
use Zend\Validator\Between;
use Zend\Validator\GreaterThan;
use Zend\Validator\LessThan;

class WorkshopRegistrationController extends AbstractMeuUniversoRegisterController
{
    const ERROR_REG_NOT_FOUND = 'x1001';
    const ERROR_REG_IS_CLOSED = 'x1002';
    const ERROR_REG_IS_NOT_EDIT = 'x1003';

    const ERROR_WORKSHOP_NOT_FOUNT = 'x2004';
    const ERROR_WORKSHOP_NO_SUBSCRIPTION = 'x2005';
    const ERROR_WORKSHOP_MULTIPLES_SUBSCRIPTION = 'x20006';

    public function indexAction()
    {
        $idReg = $this->params()->fromRoute('id_reg');
        if(!$idReg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $reg = $this->getRepository(Registration::class)->findOneBy([
            'hash' => $idReg
        ]);

        if(!$reg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        if(!$reg->isOpen()) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_IS_CLOSED,
                'id_reg' => $idReg
            ]]);
        }

        $workshops = $this->getRepository(Workshop::class)->findBy([
            'registration' => $reg->getId()
        ], ['name'=>'ASC']);

        return [
            'workshops' => $workshops,
            'registration' => $reg
        ];
    }

    public function inscricaoAction()
    {
        $idReg = $this->params()->fromRoute('id_reg');
        if(!$idReg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $reg = $this->getRepository(Registration::class)->findOneBy([
            'hash' => $idReg
        ]);

        if(!$reg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        if(!$reg->isOpen()) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_IS_CLOSED,
                'id_reg' => $idReg
            ]]);
        }

        $idWorkshop = $this->params()->fromRoute('id');
        if(!$idWorkshop) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idWorkshop
            ]]);
        }

        //valida se a oficina existe e se existe vaga
        /** @var Workshop $workshop */
        $workshop = $this->getRepository(Workshop::class)->findOneBy([
            'id' => $idWorkshop,
            'registration' => $reg->getId()
        ]);

        if(!$workshop) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_WORKSHOP_NOT_FOUNT,
                'id' => $idWorkshop
            ]]);
        }

        //Verifica se existe vaga
        if(!$workshop->hasAvailableSubscriptions()) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_WORKSHOP_NO_SUBSCRIPTION,
                'id' => $idWorkshop
            ]]);
        }

        $user = $this->getAuthenticationService()->getIdentity();
        $form = new WorkshopForm($user, $this->getEntityManager(), $reg);

        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);

            /** @var User $userSubs */
            $userSubs = null;
            if(!empty($data['user'])) {
                $idUserSub = $data['user'];
                if($idUserSub && $idUserSub != $this->getAuthenticationService()->getIdentity()->getId()) {
                    $userSubs = $this
                        ->getRepository(User::class)
                        ->findOneBy([
                            'id' => $idUserSub,
                            'parent' => $user->getId(),
                        ]);
                } else {
                    $userSubs = $this
                        ->getAuthenticationService()
                        ->getIdentity();
                }

            } else {
                $userSubs = $this
                    ->getAuthenticationService()
                    ->getIdentity();
            }

            //Verifica se o usuário já efetuou a inscrição
            $existSubscription = $this->getRepository(WorkshopSubscription::class)->findBy([
                //'event' => $workshop->getEvent()->getId(),
                'registration' => $reg->getId(),
                'user' => $userSubs->getId()
            ]);
            if($existSubscription && count($existSubscription)) {
                return $this->redirect()->toRoute('meu-universo/default', ['action'=>'erro'], ['query'=>[
                    'code' => self::ERROR_WORKSHOP_MULTIPLES_SUBSCRIPTION,
                    'id' => $idWorkshop
                ]]);
            }

            //Validação da faixa etária
            $extraValidations = true;
            $ageUserSub = 0;
            if($userSubs->getBirthDate() && $userSubs->getBirthDate() instanceof \DateTime) {
                $ageUserSub = $userSubs->getBirthDate()->diff(new \DateTime('now'))->y;
            }
            if($workshop->getMinimumAge() && $workshop->getMaximumAge()) {
                $validatorOpt['inclusive'] = true;
                $validatorOpt['min'] = $workshop->getMinimumAge();
                $validatorOpt['max'] = $workshop->getMaximumAge();

                $validatorOpt['messages'] = [
                    Between::NOT_BETWEEN_STRICT => "A faixa etária não está dentro do permitida para essa oficina. Por favor, escolher outra opção."
                ];

                $validator = new Between($validatorOpt);
                if(!$validator->isValid($ageUserSub)) {
                    $messages = $validator->getMessages();
                    $form->setMessages(['user'=>$messages]);
                    $extraValidations = false;
                }
            } elseif($workshop->getMinimumAge()) {
                $validator = new GreaterThan([
                    'min'       => $workshop->getMinimumAge(),
                    'inclusive' => true,
                    'messages' => [
                        GreaterThan::NOT_GREATER_INCLUSIVE => "A faixa etária não está dentro da permitida para essa oficina. Por favor, escolher outra opção."
                    ]
                ]);
                if(!$validator->isValid($ageUserSub)) {
                    $messages = $validator->getMessages();
                    $form->setMessages(['user'=>$messages]);
                    $extraValidations = false;
                }
            } elseif($workshop->getMaximumAge()) {
                $validator = new LessThan([
                    'max'       => $workshop->getMaximumAge(),
                    'inclusive' => true,
                    'messages' => [
                        LessThan::NOT_LESS_INCLUSIVE => "A faixa etária tem que ser acima de '%max%' anos"
                    ]
                ]);
                if(!$validator->isValid($ageUserSub)) {
                    $messages = $validator->getMessages();
                    $form->setMessages(['user'=>$messages]);
                    $extraValidations = false;
                }
            }

            if($extraValidations && $form->isValid()) {

                $subscription = new WorkshopSubscription();
                $subscription->setEvent($workshop->getEvent());
                $subscription->setWorkshop($workshop);
                $subscription->setUser($userSubs);
                $subscription->setRegistration($reg);
                //$subscription->setStatus(Status::ON_EVALUATION);

                $formAnswer = new ArrayCollection();
                if(!empty($data['form_answer'])) {
                    $formOption = $reg->getOption(Options::WORKSHOP_FORM);
                    $workshopForm = $form = $this
                        ->getEntityManager()
                        ->getRepository(EntityForm::class)
                        ->find($formOption->getValue());

                    foreach ($data['form_answer'] as $key=>$fA) {
                        $workshopAnswerForm = new WorkshopSubscriptionAnswerForm();
                        $workshopAnswerForm->setSubscription($subscription);
                        $workshopAnswerForm->setForm($workshopForm);
                        $workshopAnswerForm->setQuestion($key);
                        $workshopAnswerForm->setAnswer($fA);

                        $formAnswer->add($workshopAnswerForm);
                    }
                }
                $subscription->setFormAnswers($formAnswer);

                $this->getEntityManager()->persist($subscription);
                $this->getEntityManager()->flush();

                $tipoDoprograma = 'do';
                if( $workshop->getId() == 59 ) {
                    $tipoDoprograma = 'da oficina';
                }

                //Enviar email de confirmação
                $msg = '<p>Olá <strong>'.$userSubs->getName().'</strong>!</p>';
                $msg.= '<p>Agradecemos seu interesse em participar do Programa de Formação Audiovisual da 26ª Mostra de Cinema de Tiradentes.</p>';
                $msg.= '<p>formamos que recebemos sua inscrição para participar da atividade: <strong>'.$workshop->getName().'</strong>.
                    Até o dia 11/01/2023, entraremos em contato para divulgação dos selecionados.</p>';

                /*$msg.= '<p>Atenção: <br>
                - Prazo de confirmação: até às 24 horas após o recebimento da inscrição pelo site. <br>
                - O comprovante de confirmação deve ser assinado e enviado para e-mail  relacionamento@universoproducao.com.br <br>
                - Caso não confirme sua presença no prazo estipulado sua inscrição será considerada como DESISTÊNCIA. <br></p>';

                $confirmacao  = $this->url()->fromRoute('meu-universo/workshop', array(
                    'controller' => 'workshop-registration',
                    'action' => 'confirmacao',
                    'id_reg' => $subscription->getRegistration()->getHash(),
                    'id' => $subscription->getWorkshop()->getId()
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
                <li>6) Qualquer dúvida entre em contato: relacionamento@universoproducao.com.br </li>
                </ul>";

                $msg.= "<p>Convidamos você para participar também das outras atividades do evento.</p>";
                $msg.= "<p>A programação é gratuita e pode ser conferida no site <a href='www.cineop.com.br'>www.cineop.com.br</a>, a partir de 20 de agosto.</p>"; */

                $msg.= "<p>Atenciosamente, <br>
                        Coordenação Programa de Formação <br>
                        26ª Mostra de Cinema de Tiradentes</p>";

                //$preparedItems = $this->prepareItemsForReports($subscription);
                //$confirmacao = $this->prepareReport($preparedItems, 'workshop-confirmation' ,'pdf',true);


                $to[$user->getName()] = $user->getEmail();
                $this->mailService()->simpleSendEmail($to, "Confirmação de inscrição oficina ", $msg);

                $this->meuUniversoMessages()->flashSuccess($msg);
                return $this->redirect()->toRoute('meu-universo/default');
            }
        }

        return [
            'form' => $form,
            'reg' => $reg,
            'workshop' => $workshop
        ];
    }

    public function confirmacaoAction()
    {
        //return $this->redirect()->toRoute('meu-universo/default');

        $idReg = $this->params()->fromRoute('id_reg');
        if(!$idReg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $reg = $this->getRepository(Registration::class)->findOneBy([
            'hash' => $idReg
        ]);

        if(!$reg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        //Verificar se o prazo para confirmação está aberto
        /*if(!$reg->isOpen()) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_IS_CLOSED,
                'id_reg' => $idReg
            ]]);
        }*/

        $user = $this->getAuthenticationService()->getIdentity();

        $idWorkshopSubscription = $this->params()->fromRoute('id');
        if(!$idWorkshopSubscription) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        //Recuperar a inscrição do cara
        /** @var WorkshopSubscription $subscription */
        $subscription = $this->getRepository(WorkshopSubscription::class)->findOneBy([
            'id' => $idWorkshopSubscription,
            'registration' => $reg->getId(),
            'user' => $user->getId()
        ]);
        if(!$subscription) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_WORKSHOP_NOT_FOUNT,
                'id_reg' => $idReg
            ]]);
        }

        $confirmacao = $this->params()->fromQuery('confirmacao', null);
        if($confirmacao == 'sim') {
            $subscription->setStatus(Status::CONFIRMED);
        } elseif($confirmacao == 'nao') {
            $subscription->setStatus(Status::NOT_CONFIRMED);
        }

        $this->getEntityManager()->persist($subscription);
        $this->getEntityManager()->flush();

        return [
            'reg' => $reg,
            'subscription' => $subscription,
            'confirmacao' => $confirmacao
        ];
    }

    public function comprovanteAction()
    {
        $idReg = $this->params()->fromRoute('id_reg');
        if(!$idReg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $reg = $this->getRepository(Registration::class)->findOneBy([
            'hash' => $idReg
        ]);

        if(!$reg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        //Verificar se o prazo para confirmação está aberto
        /*if(!$reg->isOpen()) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_IS_CLOSED,
                'id_reg' => $idReg
            ]]);
        }*/

        $user = $this->getAuthenticationService()->getIdentity();

        $idWorkshopSubscription = $this->params()->fromRoute('id');
        if(!$idWorkshopSubscription) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        //Recuperar a inscrição do cara
        /** @var WorkshopSubscription $subscription */
        $subscription = $this->getRepository(WorkshopSubscription::class)->findOneBy([
            'id' => $idWorkshopSubscription,
            'registration' => $reg->getId(),
            'user' => $user->getId()
        ]);
        if(!$subscription) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_WORKSHOP_NOT_FOUNT,
                'id_reg' => $idReg
            ]]);
        }

        if($subscription->getStatus() != Status::CONFIRMED) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_WORKSHOP_NOT_FOUNT,
                'id_reg' => $idReg
            ]]);
        }

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($subscription);

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

            $workshopProgramationItems = '';
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

            /*$workshopProgramationItems = [];
            foreach ($workshopProgramation as $pro) {
                $desc = $pro->getDate()->format('d/m/Y')
                    . ' | ' . $pro->getStartTime()->format('H:i')
                    . ' às '
                    . $pro->getEndTime()->format('H:i');
                $workshopProgramationItems[] = $desc;
            }*/

            $preparedItems[]['object'] = [
                'event_name' => $obj->getEvent()->getShortName(),
                'user_name' => $obj->getUser()->getName(),
                'user_identifier' => $obj->getUser()->getIdentifier(),
                'user_birth_date' => $obj->getUser()->getBirthDate() ? $obj->getUser()->getBirthDate()->format('d/m/Y') : "",
                'user_parent_name' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getName() : "",
                'user_parent_identifier' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getIdentifier() : "",
                'workshop_name' => $obj->getWorkshop()->getName(),
                'workshop_programation' => $workshopProgramationItems
            ];
        }

        return $preparedItems;
    }
}
