<?php
namespace Admin\Controller;

use Admin\Form\Page\PageSearchForm;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Tag;
use Zend\View\Model\ViewModel;
use Admin\Form\Page\PageForm;

class PageController extends AbstractAdminController
	implements CrudInterface
{
	protected $pageForm;

	/**
	 * @return ViewModel
	 */
	public function indexAction()
	{
		$searchForm = new PageSearchForm();
		$dataAttr = $this->params()->fromQuery();
		$searchForm->setData($dataAttr);

		if(!$searchForm->isValid()) {
			$teste = $searchForm->getMessages();
		}

		$data = $searchForm->getData();

		$search = array_merge($searchForm->getData(), [
			'type' => PostType::PAGE,
			'site' => $this->getSiteIdFromUri()
		]);

		$pages = $this->search(Post::class, $search);

		$this->getViewModel()->setVariables([
            'searchForm' => $searchForm,
			'pages' => $pages,
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
		$result->setTemplate('admin/page/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		$page = $this->getRepository(Post::class)->find($id);
		if($page->getStatus() == PostStatus::TRASH) {
			$this->getEntityManager()->remove($page);
			$this->messages()->flashSuccess('Página excluída com sucesso.');
		} else {
			$page->setStatus(PostStatus::TRASH);
			$this->getEntityManager()->persist($page);
			$this->messages()->flashSuccess('Página enviada para a lixeira com sucesso.');
		}

		$this->getEntityManager()->flush();

		return $this->redirect()->toRoute('admin/page', [self::SITE_PARAM => $this->getSiteIdFromUri()]);
	}

	public function restoreAction($id)
	{
		$page = $this->getRepository(Post::class)->find($id);
		$page->setStatus(PostStatus::DRAFT);

		$this->getEntityManager()->persist($page);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Página restaurada com sucesso.');

		return $this->redirect()->toRoute('admin/page', [self::SITE_PARAM => $this->getSiteIdFromUri()]);
	}

	public function persist($data, $id=null)
	{
		$form = $this->getPageForm();
		$form->populateParentPages($this->getSiteIdFromUri(), $id);
		$form->populateMetaTemplate($this->getSiteIdFromUri());

		if($id) {
			$page = $this->getRepository(Post::class)->find($id);
			if($page->getStatus() == PostStatus::PUBLISHED) {
				$form->get('publish')->setName('save')->setValue('Salvar');
			}
		} else {
			$page = new Post();
			$page->setType(PostType::PAGE);
			$page->setAuthor($this->getAuthenticationService()->getIdentity());
			$page->setSite($this->getCurrentSite());
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$newData = $this->prepareDataPost(Post::class, $data, $page);
                $page->setData($newData);

				$this->getEntityManager()->persist($page);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Página atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Página criada com sucesso!");
					return $this->redirect()->toRoute('admin/page', [
						'action' => 'update',
						'id' => $page->getId(),
						'site' => $this->getSiteIdFromUri()
					]);
				}
			}
		} else {
			$form->setData($page->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'site' => $this->getSiteIdFromUri(),
			'page' => $page,
            'site_route' => ($this->getSiteIdFromUri()==1) ? 'universoproducao/default' : null
		]);
	}

	/**
	 * @return PageForm
	 */
	public function getPageForm()
	{
		return $this->pageForm;
	}

	public function setPageForm($pageForm)
	{
		$this->pageForm = $pageForm;
		return $this;
	}
}