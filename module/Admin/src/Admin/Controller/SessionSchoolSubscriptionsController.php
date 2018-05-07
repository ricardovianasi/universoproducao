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
use Application\Entity\Movie\Movie;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Registration\Registration;
use Application\Entity\SessionSchool\SessionSchool;
use Application\Entity\SessionSchool\SessionSchoolMovies;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
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
            $session = $this->getRepository(SessionSchool::class)->find($id);
        } else {
            $session = new SessionSchool();
        }

        $reg = null;
        if($this->params()->fromPost('registration')) {
            $reg = $this
                ->getRepository(Registration::class)
                ->find($this->params()->fromPost('registration'));
        } else {
            $reg = $session->getRegistration();
        }

        $form = new SessionSchoolForm($this->getEntityManager(), $reg);
        $programingForm = new SessionSchoolProgramingForm($this->getEntityManager());

        $noValidate = $this->params()->fromPost('no-validate', false);
        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if(!$noValidate) {
                if ($form->isValid()) {

                    $registration = null;
                    if(!empty($data['registration'])) {
                        $registration = $this->getRepository(Registration::class)->find($data['registration']);
                    }
                    $session->setEvent($registration->getEvent());
                    $session->setRegistration($registration);

                    if(!empty($data['age_range'])) {
                        $session->setAgeRange($data['age_range']);
                    } else {
                        $session->setAgeRange(null);
                    }

                    if(!empty($data['name'])) {
                        $session->setName($data['name']);
                    } else {
                        $session->setTitle(null);
                    }

                    foreach ($session->getMovies() as $m) {
                        $this->getEntityManager()->remove($m);
                    }
                    $session->getMovies()->clear();
                    if(!empty($data['movies'])) {
                        $moviesId = json_decode($data['movies'], true);
                        $count = 1;
                        foreach ($moviesId as $mId) {
                            $sessionMovie = new SessionSchoolMovies();
                            $movie = $this->getRepository(Movie::class)->find($mId['id']);
                            $sessionMovie->setMovie($movie);
                            $sessionMovie->setSession($session);
                            $sessionMovie->setOrder($count++);

                            $session->getMovies()->add($sessionMovie);
                        }
                    }

                    $this->getEntityManager()->persist($session);
                    $this->getEntityManager()->flush();

                    //programação
                    $oldProg = [];
                    foreach ($session->getProgramming() as $c) {
                        $oldProg[$c->getId()] = $c;
                    }
                    $programing = [];
                    if(!empty($data['programming'])) {
                        $programing = $data['programming'];
                    }
                    foreach ($programing as $prog) {
                        $sessProg = null;
                        if(!empty($prog['id'])) {
                            $sessProg = $this->getRepository(Programing::class)->find($prog['id']);
                        }
                        if(!$sessProg) {
                            $sessProg = new Programing();
                        }

                        $sessProg->setEvent($session->getEvent());
                        $sessProg->setType(Type::SESSION_SCHOOL);
                        $sessProg->setObjectId($session->getId());
                        if(!empty($prog['place'])) {
                            $place = $this
                                ->getRepository(Place::class)
                                ->find($prog['place']);

                            $prog['place'] = $place;
                        } else {
                            unset($prog['place']);
                        }

                        $sessProg->setData($prog);

                        $this->getEntityManager()->persist($sessProg);
                        unset($oldProg[$sessProg->getId()]);
                    }
                    foreach ($oldProg as $op) {
                        $this->getEntityManager()->remove($op);
                    }

                    $this->getEntityManager()->flush();
                    $this->getEntityManager()->refresh($session);


                    if($id) {
                        $this->messages()->success("Sessão atualizada com sucesso!");
                    } else {
                        $this->messages()->flashSuccess("Sessão criada com sucesso!");
                        return $this->redirect()->toRoute('admin/default', [
                            'controller' => 'session-school',
                            'action' => 'update',
                            'id' => $session->getId()
                        ]);
                    }
                }
            }
        } else {
            $form->setData($session->toArray());
        }

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'session' => $session,
            'programingForm' => $programingForm
        ]);
    }
}