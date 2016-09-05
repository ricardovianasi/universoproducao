<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 08:08
 */
namespace Admin\Controller;

use Admin\Form\PostSearchForm;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;

class NewsController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	/**
	 * @return ViewModel
	 */
	public function indexAction()
	{
		$searchForm = new PostSearchForm();
		$dataAttr = $this->params()->fromQuery();
		$searchForm->setData($dataAttr);

		if(!$searchForm->isValid()) {
			$teste = $searchForm->getMessages();
		}

		$data = $searchForm->getData();

		$search = array_merge($searchForm->getData(), [
			'type' => PostType::NEWS,
		]);

		$news = $this->search(Post::class, $search);

		$this->getViewModel()->setVariables([
			'searchForm' => $searchForm,
			'news' => $news
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
		$result->setTemplate('admin/news/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		$news = $this->getRepository(Post::class)->find($id);
		if($news->getStatus() == PostStatus::TRASH) {
			$this->getEntityManager()->remove($news);
			$this->messages()->flashSuccess('Notícia excluída com sucesso.');
		} else {
			$news->setStatus(PostStatus::TRASH);
			$this->getEntityManager()->persist($news);
			$this->messages()->flashSuccess('Notícia enviada para a lixeira com sucesso.');
		}

		$this->getEntityManager()->flush();

		return $this->redirect()->toRoute('admin/default', ['controller'=>'news']);
	}

	public function restoreAction($id)
	{
		$news = $this->getRepository(Post::class)->find($id);
		$news->setStatus(PostStatus::DRAFT);

		$this->getEntityManager()->persist($news);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Notícia restaurada com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'news']);
	}

	public function persist($data, $id = null)
	{
		$form = $this->getPostForm();

		if($id) {
			$news = $this->getRepository(Post::class)->find($id);
			if($news->getStatus() == PostStatus::PUBLISHED) {
				$form->get('publish')->setName('save')->setValue('Salvar');
			}
		} else {
			$news = new Post();
			$news->setType(PostType::NEWS);
			$news->setAuthor($this->getAuthenticationService()->getIdentity());
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$news->setData($this->prepareDataPost(Post::class, $data, $news));

				$this->getEntityManager()->persist($news);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Notícia atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Notícia criada com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'news',
						'action' => 'update',
						'id' => $news->getId()
					]);
				}
			}
		} else {
			$form->setData($news->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'news' => $news
		]);
	}
}