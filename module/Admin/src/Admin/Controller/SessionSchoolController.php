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
use Admin\Form\SessionSchool\SessionSchoolSearchForm;
use Application\Entity\Event\Place;
use Application\Entity\Movie\Movie;
use Application\Entity\Programing\Meta;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Registration\Registration;
use Application\Entity\SessionSchool\SessionSchool;
use Application\Entity\SessionSchool\SessionSchoolMovies;
use Doctrine\Common\Collections\ArrayCollection;

class SessionSchoolController extends AbstractAdminController
    implements CrudInterface
{
    public function indexAction()
    {
        $searchForm = new SessionSchoolSearchForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        if(empty($dataAttr)) {
            $dataAttr['event'] = $this->getDefaultEvent()?$this->getDefaultEvent()->getId():null;
        }
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(SessionSchool::class, $data);

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

                    if(!empty($data['order'])) {
                        $session->setOrder($data['order']);
                    } else {
                        $session->setOrder(null);
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

                        $metaColl = new ArrayCollection();
                        foreach ($sessProg->getMeta() as $m) {
                            $this->getEntityManager()->remove($m);
                        }
                        if(!empty($prog[Meta::ADDITIONAL_INFO])) {
                            $metaDescription = new Meta();
                            $metaDescription->setPrograming($sessProg);
                            $metaDescription->setName(Meta::ADDITIONAL_INFO);
                            $metaDescription->setValue($prog[Meta::ADDITIONAL_INFO]);

                            $metaColl->add($metaDescription);
                        }
                        $sessProg->setMeta($metaColl);
                        unset($prog[Meta::ADDITIONAL_INFO]);

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