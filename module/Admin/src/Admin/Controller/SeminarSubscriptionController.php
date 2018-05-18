<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 03/05/2018
 * Time: 09:33
 */

namespace Admin\Controller;

use Admin\Form\Seminar\SeminarSubscriptionForm;
use Admin\Form\Seminar\SeminarSubscriptionSearchForm;
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
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Seminar\Category;
use Application\Entity\Seminar\SeminarSubscription;
use Application\Entity\SessionSchool\SessionSchool;
use Application\Entity\SessionSchool\SessionSchoolMovies;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;

class SeminarSubscriptionController extends AbstractAdminController
    implements CrudInterface
{
    public function indexAction()
    {
        $dataAttr = $this->params()->fromQuery();

        $searchForm = new SeminarSubscriptionSearchForm($this->getEntityManager());
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $items = $this->search(SeminarSubscription::class, $dataAttr);

        $this->getViewModel()->setVariables([
            'items' => $items,
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
            $sub = $this->getRepository(SeminarSubscription::class)->find($id);
        } else {
            $sub = new SeminarSubscription();
        }

        $form = new SeminarSubscriptionForm($this->getEntityManager());
        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if ($form->isValid()) {
                $registration = null;
                if(!empty($data['registration'])) {
                    $registration = $this->getRepository(Registration::class)->find($data['registration']);
                }
                $sub->setEvent($registration->getEvent());
                $sub->setRegistration($registration);
                unset($data['registration']);

                //categoria
                $categoryOP = $registration->getOption(Options::SEMINAR_CATEGORY);
                $cat = $this
                    ->getRepository(Category::class)
                    ->find($categoryOP->getValue());
                $sub->setSeminarCategory($cat);

                $user = null;
                if(!empty($data['user'])) {
                    $user = $this
                        ->getRepository(User::class)
                        ->find($data['user']);
                }
                $sub->setUser($user);
                unset($data['user']);

                $this->getEntityManager()->persist($sub);
                $this->getEntityManager()->flush();


                if($id) {
                    $this->messages()->success("Inscrição atualizada com sucesso!");
                } else {
                    $this->messages()->flashSuccess("Inscrição criada com sucesso!");
                    return $this->redirect()->toRoute('admin/default', [
                        'controller' => 'seminar-subscription',
                        'action' => 'update',
                        'id' => $sub->getId()
                    ]);
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
        $item = $this->getRepository(SeminarSubscription::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);
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