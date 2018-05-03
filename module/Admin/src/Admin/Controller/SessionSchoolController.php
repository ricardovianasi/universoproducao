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
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;
use Application\Entity\Movie\Movie;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Registration\Registration;
use Application\Entity\SessionSchool\SessionSchool;
use Doctrine\Common\Collections\ArrayCollection;

class SessionSchoolController extends AbstractAdminController
    implements CrudInterface
{
    public function indexAction()
    {
        $searchForm = new SessionSchoolForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        //$items = $this->search(Programing::class, $data);

        $this->getViewModel()->setVariables([
            'items' => [],
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
        return $this->persist($data, $id);
    }

    public function deleteAction($id)
    {
        // TODO: Implement deleteAction() method.
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

                    $movies = new ArrayCollection();
                    if(!empty($data['movies'])) {
                        foreach ($data['movies'] as $mId) {
                            $movie = $this->getRepository(Movie::class)->find($mId);
                            $movies->add($movie);
                        }
                    }
                    $session->setMovies($movies);

                    $this->getEntityManager()->persist($session);
                    $this->getEntityManager()->flush();


                    //programação
                    $oldProg = [];
                    foreach ($session->getPrograming() as $c) {
                        $oldProg[$c->getId()] = $c;
                    }
                    $programing = [];
                    if(!empty($data['programing'])) {
                        $programing = $data['programing'];
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