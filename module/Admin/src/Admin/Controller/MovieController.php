<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 08:08
 */
namespace Admin\Controller;

use Admin\Form\Movie\MovieForm;
use Admin\Form\Movie\MovieFormSearch;
use Admin\Form\Movie\OptionsForm;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\Options;

class MovieController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	/**
	 * @return ViewModel
	 */
	public function indexAction()
	{
	    $searchForm = new MovieFormSearch($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(Movie::class, $data);;
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
		$result->setTemplate('admin/movie/create.phtml');

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
		$form = new MovieForm($this->getEntityManager());

		if($id) {
			$movie = $this->getRepository(Movie::class)->find($id);
			$form->setRegistration($movie->getRegistration());
		} else {
			$movie = new Movie();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$movie->setData($data);
				$this->getEntityManager()->persist($movie);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Filme atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Filme criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'movie',
						'action' => 'update',
						'id' => $movie->getId()
					]);
				}
			}
		} else {
			$form->setData($movie->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'movie' => $movie
		]);
	}
}