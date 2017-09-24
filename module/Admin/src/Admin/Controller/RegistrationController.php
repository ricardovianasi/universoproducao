<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 08:08
 */
namespace Admin\Controller;

use Admin\Form\Movie\OptionsForm;
use Admin\Form\Registration\RegistrationForm;
use Application\Entity\Event\Event;
use Application\Entity\Movie\Options;
use Application\Entity\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Model\JsonModel;

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
        $op = $this->getRepository(Options::class)->find($id);
        $this->getEntityManager()->remove($op);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Opção excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'registration']);
	}

	public function persist($data, $id = null)
	{
		$form = new RegistrationForm($this->getEntityManager());

		if($id) {
			$registration = $this->getRepository(Registration::class)->find($id);
		} else {
			$registration = new Registration();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
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

                //$this->reorder($data['position']);

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