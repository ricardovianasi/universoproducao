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
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Seminar\Category;
use Application\Entity\Seminar\Debate;
use Application\Entity\Seminar\SeminarSubscription;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
use Application\Entity\User\User;

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
            'searchForm' => $searchForm,
            'searchData' => $dataAttr,
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
            ->getRepository(SeminarSubscription::class)
            ->find($id);

        $this->getEntityManager()->remove($subscription);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Inscrição excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', [
            'controller' => 'seminar-subscription',
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

        $registration = null;
        if($regID = $this->params()->fromPost('registration')) {
            $registration = $this->getRepository(Registration::class)->find($regID);
        } else {
            $registration = $sub->getRegistration();
        }

        $debates =[];
        if($registration) {
            $debates = $this
                ->getRepository(Debate::class)
                ->findBy([
                    'event' => $registration->getEvent()->getId()
                ]);
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

                foreach ($data['debates'] as $d) {
                    $deb = $this->getRepository(Debate::class)->find($d);
                    $sub->getDebates()->add($deb);
                }
                unset($data['debates']);

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

        $selectedDebates = [];
        foreach ($sub->getDebates() as $d) {
            $selectedDebates[] = $d->getId();
        }

        return $this->getViewModel()->setVariables([
            'form'      => $form,
            'sub'       => $sub,
            'debates'   => $debates,
            'selectedDebates' => $selectedDebates
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

    public function exportListAction()
    {
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(SeminarSubscription::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'seminar-subscription-list' ,'xlsx');
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


            $debates = [];
            foreach ($obj->getDebates() as $d) {
                $debates[] = $d->getTitle();
            }

            $preparedItems[]['object'] = [
                'id' => $obj->getID(),
                'seminar' => $obj->getSeminarCategory()->getName(),
                'event_name' => $obj->getEvent()->getShortName(),
                'event_full_name' => $obj->getEvent()->getFullName(),
                'user_name' => $obj->getUser()->getName(),
                'user_identifier' => $obj->getUser()->getIdentifier(),
                'user_birth_date' => $obj->getUser()->getBirthDate() ? $obj->getUser()->getBirthDate()->format('d/m/Y') : "",
                'user_parent_name' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getName() : "",
                'user_parent_identifier' => $obj->getUser()->getParent() ? $obj->getUser()->getParent()->getIdentifier() : "",
                'user_address' => $obj->getUser()->getFullAddress(),
                'user_phones' => $obj->getUser()->getFullPhones(),
                'user_email' => $obj->getUser()->getEmail(),
                'created_at' => $obj->getCreatedAt()->format('d/m/Y H:i:s'),
                'debates' => implode('; ', $debates)
            ];
        }

        return $preparedItems;
    }
}