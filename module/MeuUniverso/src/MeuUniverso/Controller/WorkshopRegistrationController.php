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
    const ERROR_WORKSHOP_MULTIPLES_SUBSCRIPTION = 'x2006';

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
                    Between::NOT_BETWEEN_STRICT => "A sua faixa etária não está dentro do permitida para essa oficina. Por favor, escolher outra opção."
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
                        GreaterThan::NOT_GREATER_INCLUSIVE => "A sua faixa etária não está dentro da permitida para essa oficina. Por favor, escolher outra opção."
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

                //Enviar email de confirmação
                $msg = '<p>Olá <strong>'.$userSubs->getName().'</strong>!</p>';
                $msg.= "<p>Agradecemos seu interesse em participar do Programa de Formação da 13ª CineBH e 10º Brasil CineMundi.</p>";
                $msg.= "<p>Informamos que recebemos sua inscrição para participar da oficina: ".$workshop->getName().". Até o dia 11/09/2019, entraremos em contato para divulgação dos selecionados.</p>";

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
            $workshopProgramationItems = [];
            foreach ($workshopProgramation as $pro) {
                $desc = $pro->getDate()->format('d/m/Y')
                    . ' | ' . $pro->getStartTime()->format('H:i')
                    . ' às '
                    . $pro->getEndTime()->format('H:i');
                $workshopProgramationItems[] = $desc;
            }

            $preparedItems[]['object'] = [
                'event_name' => $obj->getEvent()->getShortName(),
                'user_name' => $obj->getUser()->getName(),
                'user_identifier' => $obj->getUser()->getIdentifier(),
                'user_birth_date' => $obj->getUser()->getBirthDate() ? $obj->getUser()->getBirthDate()->format('d/m/Y') : "",
                'user_parent_name' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getName() : "",
                'user_parent_identifier' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getIdentifier() : "",
                'workshop_name' => $obj->getWorkshop()->getName(),
                'workshop_programation' => implode(';', $workshopProgramationItems)
            ];
        }

        return $preparedItems;
    }
}