<?php
namespace Admin\Controller;

use Admin\Form\Workshop\ManagerForm;
use Application\Entity\City;
use Application\Entity\Workshop\Manager;

class WorkshopManagerController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new ManagerForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$items = $this->search(Manager::class, $data);

		$this->getViewModel()->setVariables([
			'items' => $items,
            'searchForm' => $searchForm,
		]);

		return $this->getViewModel();
	}

	public function createAction($data)
	{
		return $this->persist($data);
	}

	public function updateAction($id, $data)
	{
		$result = $this->persist($data, $id);
		$result->setTemplate('admin/workshop-manager/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$manager = $this->getRepository(Manager::class)->find($id);
		$this->getEntityManager()->remove($manager);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Responsável excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'workshop-manager']);
	}

	public function persist($data, $id = null)
	{
		$form = new ManagerForm($this->getEntityManager());

		if($id) {
			$manager = $this->getRepository(Manager::class)->find($id);
		} else {
			$manager = new Manager();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
                $dataValida = $form->getData();
                if(isset($dataValida['city'])) {
                    $city = $this
                        ->getRepository(City::Class)
                        ->find($dataValida['city']);

                    $dataValida['city'] = $city;
                }

				$manager->setData($dataValida);
				$this->getEntityManager()->persist($manager);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Responsável atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Responsável criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'workshop-manager',
						'action' => 'update',
						'id' => $manager->getId()
					]);
				}
			}
		} else {
			$form->setData($manager->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'manager' => $manager
		]);
	}


}