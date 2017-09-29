<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 08:08
 */
namespace Admin\Controller;

use Admin\Form\Registration\MovieRegistrationForm;
use Admin\Form\Registration\RegistrationForm;
use Application\Entity\Event\Event;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Type;
use Doctrine\Common\Collections\ArrayCollection;

class RegistrationController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	/**
	 * @return ViewModel
	 */
	public function indexAction()
	{
	    $searchForm = new RegistrationForm();
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(Registration::class, $data);
        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm
        ], ['position'=>'ASC', 'startDate'=>'DESC', 'id'=>'DESC']);

        return $this->getViewModel();
	}

	public function createAction($data)
	{
		return $this->persist($data);
	}

	public function updateAction($id, $data)
	{
		$result = $this->persist($data, $id);
		$result->setTemplate('admin/registration/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
        $reg = $this->getRepository(Registration::class)->find($id);

        $this->getEntityManager()->remove($reg);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Inscrição excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'registration']);
	}

	public function persist($data, $id = null)
	{
	    $formType = "";

        if($id) {
            $registration = $this->getRepository(Registration::class)->find($id);
            $formType = $registration->getType();
        } else {
            $registration = new Registration();
        }

        if($this->params()->fromPost('type')) {
            $formType = $this->params()->fromPost('type');
            $registration->setType($formType);
        }
	    $noValidate = $this->params()->fromPost('no-validate', false);

        switch ($formType) {
            case Type::MOVIE:
                $form = new MovieRegistrationForm($this->getEntityManager());
                break;
            default:
                $form = new RegistrationForm($this->getEntityManager());
                break;
        }

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if(!$noValidate) {
                if($form->isValid()) {
                    $events = new ArrayCollection();
                    foreach ($data['events'] as $e) {
                        $event = $this->getRepository(Event::class)->find($e);
                        $events->add($event);
                    }
                    $registration->setEvents($events);
                    unset($data['events']);

                    if(!empty($data['start_date']) && !empty($data['hour_start'])) {
                        $data['start_date'] = $data['start_date'].' '.$data['hour_start'];
                    }

                    if(!empty($data['end_date']) && !empty($data['hour_end'])) {
                        $data['end_date'] = $data['end_date'].' '.$data['hour_end'];
                    }

                    $optionsCollection = new ArrayCollection();
                    foreach ($registration->getOptions() as $op) {
                        $this->getEntityManager()->remove($op);
                    }
                    if(!empty($data['options'])) {
                        foreach ($data['options'] as $key=>$opValue) {
                            if(!empty($opValue)) {
                                $option = new Options();
                                $option->setRegistration($registration);
                                $option->setName($key);
                                $option->setValue($opValue);

                                $optionsCollection->add($option);
                            }
                        }
                    }
                    $data['options'] = $optionsCollection;

                    $registration->setData($data);
                    $this->getEntityManager()->persist($registration);
                    $this->getEntityManager()->flush();

                    if($id) {
                        $this->messages()->success("Inscrição atualizada com sucesso!");
                    } else {
                        $this->messages()->flashSuccess("Inscrição criada com sucesso!");
                        return $this->redirect()->toRoute('admin/default', [
                            'controller' => 'registration',
                            'action' => 'update',
                            'id' => $registration->getId()
                        ]);
                    }
                }
            }
		} else {
			$form->setData($registration->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'registration' => $registration
		]);
	}

	protected function reorder($position) {
	    $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update(Registration::class, 'r')
            ->set('r.position', ':null')
            ->where('r.position = :position')
            ->setParameter('null', NULL)
            ->setParameter('position', $position);

        return $qb->getQuery()->execute();
    }
}