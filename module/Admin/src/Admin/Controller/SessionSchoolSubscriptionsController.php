<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 03/05/2018
 * Time: 09:33
 */

namespace Admin\Controller;

use Admin\Form\SessionSchool\SessionSchoolForm;
use Admin\Form\SessionSchool\SessionSchoolProgramingForm;
use Admin\Form\SessionSchool\SessionSchoolSubscriptionForm;
use Admin\Form\SessionSchool\SessionSchoolSubscriptionSearchForm;
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;
use Application\Entity\Institution\Institution;
use Application\Entity\Movie\Movie;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Registration\Registration;
use Application\Entity\SessionSchool\SessionSchool;
use Application\Entity\SessionSchool\SessionSchoolMovies;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;

class SessionSchoolSubscriptionsController extends AbstractAdminController
    implements CrudInterface
{
    public function indexAction()
    {
        $dataAttr = $this->params()->fromQuery();

        $event = null;
        if(!empty($dataAttr['event'])) {
            $event = $dataAttr['event'];
        }

        $searchForm = new SessionSchoolSubscriptionSearchForm($this->getEntityManager(), $event);
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $items = $this->search(SessionSchoolSubscription::class, $dataAttr);

        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm,
            'repository' => $this->getRepository(SessionSchool::class)
        ]);

        return $this->getViewModel();
    }

    public function createAction($data)
    {
        return $this->persist($data);
    }

    public function updateAction($id, $data)
    {
        return $this->persist($data, $id);
    }

    public function deleteAction($id)
    {
        $subscription = $this
            ->getRepository(SessionSchoolSubscription::class)
            ->find($id);

        $this->getEntityManager()->remove($subscription);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Inscrição excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', [
            'controller' => 'session-school-subscriptions',
            'action' => 'index'
        ]);
    }

    public function persist($data, $id = null)
    {
        if($id) {
            $sub = $this->getRepository(SessionSchoolSubscription::class)->find($id);
        } else {
            $sub = new SessionSchoolSubscription();
        }

        $form = new SessionSchoolSubscriptionForm($this->getEntityManager());

        $noValidate = $this->params()->fromPost('no-validate', false);
        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if(!$noValidate) {
                if ($form->isValid()) {

                    $registration = null;
                    if(!empty($data['registration'])) {
                        $registration = $this->getRepository(Registration::class)->find($data['registration']);
                    }
                    $sub->setEvent($registration->getEvent());
                    $sub->setRegistration($registration);
                    unset($data['registration']);

                    $user = null;
                    if(!empty($data['user'])) {
                        $user = $this
                            ->getRepository(User::class)
                            ->find($data['user']);
                    }
                    $sub->setUser($user);
                    unset($data['user']);

                    $instituition = null;
                    if(!empty($data['instituition'])) {
                        if(!empty($data['instituition']['id'])) {
                            $instituition = $this
                                ->getRepository(Institution::class)
                                ->find($data['instituition']['id']);
                        } else {
                            $instituition = new Institution();
                        }
                        $instituition->setData($data['instituition']);
                    }
                    $sub->setInstituition($instituition);
                    unset($data['instituition']);

                    $sub->setData($data);

                    $this->getEntityManager()->persist($sub);
                    $this->getEntityManager()->flush();


                    if($id) {
                        $this->messages()->success("Inscrição atualizada com sucesso!");
                    } else {
                        $this->messages()->flashSuccess("Inscrição criada com sucesso!");
                        return $this->redirect()->toRoute('admin/default', [
                            'controller' => 'session-school-subscription',
                            'action' => 'update',
                            'id' => $sub->getId()
                        ]);
                    }
                }
            }
        } else {
            $form->setData($sub->toArray());
        }

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'sub' => $sub
        ]);
    }

    public function termSheetAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $id = $this->params()->fromRoute('id');
        $item = $this->getRepository(SessionSchoolSubscription::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);

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