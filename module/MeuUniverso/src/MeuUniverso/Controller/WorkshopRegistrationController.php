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
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\User\User;
use Application\Entity\Workshop\Workshop;
use Application\Entity\Workshop\WorkshopSubscription;
use Application\Entity\Workshop\WorkshopSubscriptionAnswerForm;
use Doctrine\Common\Collections\ArrayCollection;
use MeuUniverso\Form\WorkshopForm;
use Zend\Validator\Between;

class WorkshopRegistrationController extends AbstractMeuUniversoRegisterController
{
    const ERROR_REG_NOT_FOUND = 'x1001';
    const ERROR_REG_IS_CLOSED = 'x1002';
    const ERROR_REG_IS_NOT_EDIT = 'x1003';

    const ERROR_WORKSHOP_NOT_FOUNT = 'x2004';
    const ERROR_WORKSHOP_NO_SUBSCRIPTION = 'x2005';

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
        ], ['name'=>'DESC']);

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
                $userSubs = $this
                    ->getAuthenticationService()
                    ->getIdentity();
            } else {
                $idUserSub = $data['user'];
                if($idUserSub && $idUserSub != $this->getAuthenticationService()->getIdentity()->getId()) {
                    $userSubs = $this
                        ->getRepository(User::class)
                        ->findOneBy([
                            'id' => $user->getId(),
                            'parent' => $idUserSub
                        ]);
                } else {
                    $userSubs = $this
                        ->getAuthenticationService()
                        ->getIdentity();
                }
            }

            //Verifica se o usuário já efetuou a inscrição

            //Validação da faixa etária
            $extraValidations = true;
            if($workshop->getMinimumAge() || $workshop->getMaximumAge()) {
                $ageUserSub = $userSubs->getBirthDate()->diff(new \DateTime('now'))->y;

                $validatorOpt['inclusive'] = true;
                if($workshop->getMinimumAge()) {
                    $validatorOpt['min'] = $workshop->getMinimumAge();
                } else {
                    $validatorOpt['min'] = 0;
                }

                if($workshop->getMaximumAge()) {
                    $validatorOpt['max'] = $workshop->getMaximumAge();
                } else {
                    $validatorOpt['max'] = 100;
                }

                $validator = new Between($validatorOpt);
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
                $msg.= "<p>Agradecemos seu interesse em participar da <strong>21ª Mostra de Cinema de Tiradentes.</strong>.</p>";
                $msg.= "<p>Informamos que recebemos sua inscrição para participar da Oficina: ".$workshop->getName().". Até o dia 05/01/2018, entraremos em contato para divulgação dos selecionados.</p>";

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

    public function deleteAction()
    {
        return [];
    }
}