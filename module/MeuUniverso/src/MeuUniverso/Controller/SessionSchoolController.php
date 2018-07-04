<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use Application\Entity\Institution\Institution;
use Application\Entity\Programing\Programing;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\SessionSchool\SessionSchool;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
use MeuUniverso\Form\SessionSchoolSubscriptionForm;
use Zend\Validator\LessThan;

class SessionSchoolController extends AbstractMeuUniversoRegisterController
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

        $sessions = $this->getRepository(SessionSchool::class)->findBy([
            'registration' => $reg->getId(),
        ], ['order'=>'ASC']);

        $sessionSchoolRepository = $this->getRepository(SessionSchool::class);
        return [
            'sessions' => $sessions,
            'registration' => $reg,
            'sessionSchoolRepository' => $sessionSchoolRepository
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

        $idSessionProg = $this->params()->fromRoute('id');
        if(!$idSessionProg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        //valida se a oficina existe e se existe vaga
        /** @var Programing $sessionProg */
        $sessionProg = $this->getRepository(Programing::class)->findOneBy([
            'id' => $idSessionProg
        ]);

        if(!$sessionProg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id' => $idSessionProg
            ]]);
        }

        /** @var SessionSchool $session */
        $session = $this->getRepository(SessionSchool::class)->findOneBy([
            'id' => $sessionProg->getObjectId(),
            'registration' => $reg->getId()
        ]);
        if(!$session) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id' => $idSessionProg
            ]]);
        }

        //Verifica se existe vaga
        $sessionSchoolRepository = $this->getRepository(SessionSchool::class);
        if(!$sessionSchoolRepository->hasAvailableSubscriptions($sessionProg->getId())) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_SESSION_NO_SUBSCRIPTION,
                'id' => $idSessionProg
            ]]);
        }

        $user = $this->getAuthenticationService()->getIdentity();
        $form = new SessionSchoolSubscriptionForm($this->getEntityManager());

        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            $extraValidations = true;

            $participantsValidator = new LessThan([
                'max' => ($sessionProg->getAvailablePlaces() - $sessionSchoolRepository->getTotalSubscriptionsSession($sessionProg->getId())),
                'inclusive' => true,
                'messages' => [
                    LessThan::NOT_LESS_INCLUSIVE => "Existem '%max%' vagas restantes"
                ]
            ]);
            if(!$participantsValidator->isValid($data['participants'])) {
                $messages = $participantsValidator->getMessages();
                $form->setMessages(['participants'=>$messages]);
                $extraValidations = false;
            }

            if($form->isValid() && $extraValidations) {
                $subscription = new SessionSchoolSubscription();
                $subscription->setEvent($session->getEvent());
                $subscription->setRegistration($reg);
                $subscription->setUser($user);
                $subscription->setSession($session);
                $subscription->setSessionProgramming($sessionProg);

                $instituition = null;
                if(!empty($data['instituition'])) {
                    $instituition = new Institution();
                    $instituition->setData($data['instituition']);
                }
                $subscription->setInstituition($instituition);
                unset($data['instituition']);

                $subscription->setData($data);

                $this->getEntityManager()->persist($subscription);
                $this->getEntityManager()->flush();

                //Enviar email de confirmação
                $msg = '<p>Olá <strong>'.$user->getName().'</strong>!</p>';
                $msg.= "<p>Agradecemos seu interesse em participar da <strong>12ª CineBH</strong>.</p>";
                $msg.= "<p>Informamos que recebemos sua inscrição para participar da sessão: ".$session->getName().".</p>";
                $msg.= "<p>Favor imprimir, assinar e enviar este documento via fax (31.3282.2366) ou via email (cine-escola@universoproducao.com.br) até 48 horas após o preenchimento da inscrição no site. </p>";
                $msg.= "<p><strong>ATENÇÃO: </strong></p>";
                $msg.= "<p><ul>
                    <li>As vagas na sessão só serão garantidas após o recebimento do Termo de Compromisso, conforme item anterior.</li>
                    <li>Será considerada a ordem cronológica do recebimento do Termo de Compromisso assinado por sessão até que se complete a lotação da sala.</li>
                </ul></p>";

                $this->getEntityManager()->refresh($subscription);

                $preparedItems = $this->prepareItemsForReports($subscription);
                $confirmacao = $this->prepareReport($preparedItems, 'session-confirmation' ,'pdf',true);

                $to[$user->getName()] = $user->getEmail();
                $this->mailService()->simpleSendEmail($to, "Confirmação de inscrição Cine-Expressão ", $msg, $confirmacao);

                $cc['Cine-Escola'] = 'cine-escola@universoproducao.com.br';
                $this->mailService()->simpleSendEmail($cc, "Inscrição Cine-Expressão - CineBH", $msg, $confirmacao);

                $this->meuUniversoMessages()->flashSuccess($msg);
                return $this->redirect()->toRoute('meu-universo/default');
            }
        }

        return [
            'form' => $form,
            'reg' => $reg,
            'session' => $session,
            'sessionSchoolRepository' => $sessionSchoolRepository
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

        $idSessionSchoolSubscription = $this->params()->fromRoute('id');
        if(!$idSessionSchoolSubscription) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        //Recuperar a inscrição do cara
        /** @var WorkshopSubscription $subscription */
        $subscription = $this->getRepository(SessionSchoolSubscription::class)->findBy([
            'id' => $idSessionSchoolSubscription,
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

        return $this->prepareReport($preparedItems, 'session-confirmation' ,'pdf');
    }

    protected function prepareItemsForReports($items)
    {
        if(!is_array($items)) {
            $items = [$items];
        }

        $preparedItems = [];
        foreach ($items as $obj) {

            /** @var SessionSchoolSubscription $obj */
            $obj = $obj;

            $sessionsMovies = [];
            foreach ($obj->getSession()->getMovies() as $sm) {
                $sessionsMovies[] = $sm->getMovie()->getTitle();
            }

            $preparedItems[]['object'] = [
                'event_name' => $obj->getEvent()->getShortName(),
                'instituition_social_name' => $obj->getInstituition()->getSocialName(),
                'instituition_cnpj' => $obj->getInstituition()->getCnpj(),
                'instituition_address' => $obj->getInstituition()->getAddress(),
                'instituition_city' => $obj->getInstituition()->getCity(),
                'instituition_uf' => $obj->getInstituition()->getUf(),
                'instituition_cep' => $obj->getInstituition()->getCep(),
                'instituition_phone' => $obj->getInstituition()->getPhone(),
                'instituition_mobile_phone' => $obj->getInstituition()->getMobilePhone(),
                'instituition_email' => $obj->getInstituition()->getEmail(),
                'instituition_direction' => $obj->getInstituitionDirection(),
                'responsible' => $obj->getResponsible(),
                'responsible_phone' => $obj->getResponsiblePhone(),
                'responsible_mobile_phone' => $obj->getResponsibleMobilePhone(),
                'session_name' => $obj->getSession()->getName(),
                'session_movies' => implode(' - ', $sessionsMovies),
                'session_programming_date' => $obj->getSessionProgramming()->getDate()->format('d/m/Y'),
                'session_programming_hour' => $obj->getSessionProgramming()->getStartTime()->format('H:i'),
                'session_programming_place' => $obj->getSessionProgramming()->getPlace()->getName(),
                'participants' => $obj->getParticipants(),
                'serie' => $obj->getSeriesAge(),
                'created_at' => $obj->getCreatedAt()->format('d/m/Y H:i:s')
            ];
        }

        return $preparedItems;
    }
}