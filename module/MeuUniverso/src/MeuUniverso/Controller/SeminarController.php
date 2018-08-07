<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Seminar\Category;
use Application\Entity\Seminar\Debate;
use Application\Entity\Seminar\SeminarSubscription;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
use Application\Entity\User\User;
use MeuUniverso\Form\SeminarForm;

class SeminarController extends AbstractMeuUniversoRegisterController
{
    const ERROR_REG_NOT_FOUND = 'x1001';
    const ERROR_REG_IS_CLOSED = 'x1002';
    const ERROR_REG_IS_NOT_EDIT = 'x1003';

    const ERROR_WORKSHOP_NOT_FOUNT = 'x2004';
    const ERROR_WORKSHOP_NO_SUBSCRIPTION = 'x2005';
    const ERROR_WORKSHOP_MULTIPLES_SUBSCRIPTION = 'x2006';

    const ERROR_SEMINAR_MULTIPLES_SUBSCRIPTION = 'x3006';

    public function inscricaoAction()
    {
        $idReg = $this->params()->fromRoute('id_reg');
        if(!$idReg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        /** @var Registration $reg */
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

        $avaliable = 0;
        $avOp = $reg->getOption(Options::SEMINAR_AVALIABLE);
        if($avOp) {
            $avaliable = (int) $avOp->getValue();
        }

        //Tipo do seminário: completo ou lista
        $seminarType = $reg->getOption(Options::SEMINAR_TYPE, 'full');
        $debates = [];
        if($seminarType == 'selected') {
            $debates = $this
                ->getRepository(Debate::class)
                ->findBy([
                    'event' => $reg->getEvent()->getId()
                ]);

        } else {
            //Vagas
            //Total de vagas
            $totalSub = $this->getRepository(SeminarSubscription::class)->getTotalSubscription($reg->getId());

            if($totalSub >= $avaliable) {
                return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                    'code' => self::ERROR_WORKSHOP_NO_SUBSCRIPTION,
                    'id_reg' => $idReg
                ]]);
            }
        }

        $user = $this->getAuthenticationService()->getIdentity();
        $form = new SeminarForm($user, $this->getEntityManager(), $reg);

        if($this->getRequest()->isPost()) {
            $data = (array)$this->getRequest()->getPost();
            $form->setData($data);

            $extraValidations = false;
            if($seminarType == 'selected') {
                //confere se tem vaga para os debates selecionados
                if($this->getRequest()->isPost()) {
                    $selectedDebates = $this->params()->fromPost('debates');
                    foreach ($selectedDebates as $d) {
                        $totalSubDeb = $this
                            ->getRepository(SeminarSubscription::class)
                            ->getTotalSubscriptionByDebate($d);

                        if($totalSubDeb >= $avaliable) {
                            $form->setMessages(['debates[]'=>['erro'=>'Não há vagas para os debates escolhidos']]);
                            $extraValidations = true;
                        }
                    }
                }
            }

            /** @var User $userSubs */
            $userSubs = null;
            if(empty($data['user'])) {
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
            $existSubscription = $this->getRepository(SeminarSubscription::class)->findBy([
                'event' => $reg->getEvent()->getId(),
                'user' => $userSubs->getId()
            ]);
            if($existSubscription && count($existSubscription)) {
                return $this->redirect()->toRoute('meu-universo/default', ['action'=>'erro'], ['query'=>[
                    'code' => self::ERROR_WORKSHOP_MULTIPLES_SUBSCRIPTION
                ]]);
            }

            if(!$extraValidations && $form->isValid()) {
                $subscription = new SeminarSubscription();
                $subscription->setRegistration($reg);
                $subscription->setEvent($reg->getEvent());
                $subscription->setUser($userSubs);

                $categoryOp = $reg->getOption(Options::SEMINAR_CATEGORY);
                $category = $this->getRepository(Category::class)->find($categoryOp->getValue());
                $subscription->setSeminarCategory($category);

                if($seminarType == 'selected') {
                    foreach ($data['debates'] as $d) {
                        $deb = $this->getRepository(Debate::class)->find($d);
                        $subscription->getDebates()->add($deb);
                    }
                }

                $this->getEntityManager()->persist($subscription);
                $this->getEntityManager()->flush();

                //Enviar email de confirmação
                $msg = '<p>Olá <strong>'.$user->getName().'</strong>!</p>';
                $msg.= "<p>Agradecemos seu interesse em participar do Programa de Formação Audiovisual da <strong>12ª CineBH</strong> e <strong>e 9º Braisl CineMundi</strong>.</p>";
                $msg.= "<p><strong>ATENÇÃO: </strong></p>";
                $msg.= "<p>Você deverá apresentar documento original com foto e o comprovante de inscrição impresso e assinado para retirar sua credencial no dia 29 de agosto – quarta, de 10h às 13h, no balcão de credenciamento localizado no Jardim Externo do Palácio das Artes (piso inferior).</p>";

                $this->getEntityManager()->refresh($subscription);

                $preparedItems = $this->prepareItemsForReports($subscription);
                $confirmacao = $this->prepareReport($preparedItems, 'seminar-confirmation' ,'pdf',true);

                $to[$user->getName()] = $user->getEmail();
                $this->mailService()->simpleSendEmail($to, "Debates - Confirmação de inscrição", $msg, $confirmacao);

                $this->meuUniversoMessages()->flashSuccess($msg);
                return $this->redirect()->toRoute('meu-universo/default');
            }
        }

        return [
            'type' => $seminarType,
            'debates' => $debates,
            'form' => $form,
            'reg' => $reg,
            'repository' => $this->getRepository(SeminarSubscription::class)
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

        $user = $this->getAuthenticationService()->getIdentity();

        $idSessionSeminarSubscription = $this->params()->fromRoute('id');
        if(!$idSessionSeminarSubscription) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        //Recuperar a inscrição do cara
        /** @var SeminarSubscription $subscription */
        $subscription = $this->getRepository(SeminarSubscription::class)->findBy([
            'id' => $idSessionSeminarSubscription,
            'registration' => $reg->getId(),
            'user' => $user->getId()
        ]);
        if(!$subscription) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_WORKSHOP_NOT_FOUNT,
                'id_reg' => $idReg
            ]]);
        }

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($subscription);

        return $this->prepareReport($preparedItems, 'seminar-confirmation' ,'pdf');
    }

    protected function prepareItemsForReports($items)
    {
        if(!is_array($items)) {
            $items = [$items];
        }

        $preparedItems = [];
        foreach ($items as $obj) {

            /** @var SeminarSubscription $obj */
            $obj = $obj;

            $preparedItems[]['object'] = [
                'seminar' => $obj->getSeminarCategory()->getName(),
                'event_name' => $obj->getEvent()->getShortName(),
                'event_full_name' => $obj->getEvent()->getFullName(),
                'user_name' => $obj->getUser()->getName(),
                'user_identifier' => $obj->getUser()->getIdentifier(),
                'user_birth_date' => $obj->getUser()->getBirthDate() ? $obj->getUser()->getBirthDate()->format('d/m/Y') : "",
                'user_parent_name' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getName() : "",
                'user_parent_identifier' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getIdentifier() : "",
                'created_at' => $obj->getCreatedAt()->format('d/m/Y H:i:s')
            ];
        }

        return $preparedItems;
    }
}