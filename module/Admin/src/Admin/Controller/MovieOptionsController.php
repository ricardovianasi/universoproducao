<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 08:08
 */
namespace Admin\Controller;

use Admin\Form\Movie\OptionsForm;
use Application\Entity\Movie\Options;

class MovieOptionsController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	/**
	 * @return ViewModel
	 */
	public function indexAction()
	{
	    $searchForm = new OptionsForm();
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(Options::class, $data);;
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
		$result = $this->persist($data, $id);
		$result->setTemplate('admin/movie-options/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
        $op = $this->getRepository(Options::class)->find($id);
        $this->getEntityManager()->remove($op);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Opção excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'movie-options']);
	}

	public function persist($data, $id = null)
	{
		$form = new OptionsForm();

		if($id) {
			$option = $this->getRepository(Options::class)->find($id);
		} else {
			$option = new Options();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$option->setData($data);
				$this->getEntityManager()->persist($option);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Opção atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Opção criada com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'movie-options',
						'action' => 'update',
						'id' => $option->getId()
					]);
				}
			}
		} else {
			$form->setData($option->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'option' => $option
		]);
	}
}