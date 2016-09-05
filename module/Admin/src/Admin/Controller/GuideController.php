<?php
namespace Admin\Controller;

use Admin\Form\PostSearchForm;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Post\Post;

class GuideController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
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
			'type' => PostType::GUIDE,
			'site' => $this->getSiteIdFromUri()
		]);

		$guides = $this->search(Post::class, $search);

		$this->getViewModel()->setVariables([
			'searchForm' => $searchForm,
			'guides' => $guides,
			'site' => $this->getSiteIdFromUri()
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
		$result->setTemplate('admin/guide/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		$guide = $this->getRepository(Post::class)->find($id);
		if($guide->getStatus() == PostStatus::TRASH) {
			$this->getEntityManager()->remove($guide);
			$this->messages()->flashSuccess('Guia excluído com sucesso.');
		} else {
			$guide->setStatus(PostStatus::TRASH);
			$this->getEntityManager()->persist($guide);
			$this->messages()->flashSuccess('Guia enviado para a lixeira com sucesso.');
		}

		$this->getEntityManager()->flush();

		return $this->redirect()->toRoute('admin/guide', [self::SITE_PARAM => $this->getSiteIdFromUri()]);
	}

	public function restoreAction($id)
	{
		$guide = $this->getRepository(Post::class)->find($id);
		$guide->setStatus(PostStatus::DRAFT);

		$this->getEntityManager()->persist($guide);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Guia restaurada com sucesso.');

		return $this->redirect()->toRoute('admin/guide', [self::SITE_PARAM => $this->getSiteIdFromUri()]);
	}

	public function persist($data, $id = null)
	{
		$form = $this->getPostForm();

		if($id) {
			$guide = $this->getRepository(Post::class)->find($id);
			if($guide->getStatus() == PostStatus::PUBLISHED) {
				$form->get('publish')->setName('save')->setValue('Salvar');
			}
		} else {
			$guide = new Post();
			$guide->setType(PostType::GUIDE);
			$guide->setAuthor($this->getAuthenticationService()->getIdentity());
			$guide->setSite($this->getCurrentSite());
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$guide->setData($this->prepareDataPost(Post::class, $data));
				$this->getEntityManager()->persist($guide);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Guia atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Guia criada com sucesso!");
					return $this->redirect()->toRoute('admin/guide', [
						'action' => 'update',
						'id' => $guide->getId(),
						'site' => $this->getSiteIdFromUri()
					]);
				}
			}
		} else {
			$form->setData($guide->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'site' => $this->getSiteIdFromUri(),
			'guide' => $guide
		]);
	}

}