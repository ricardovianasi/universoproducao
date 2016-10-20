<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 01/03/2016
 * Time: 09:45
 */

namespace Admin\Controller;

use Application\Entity\Site\Language;
use Application\Entity\Site\Menu\Item;
use Application\Entity\Site\Menu\Menu;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Model\JsonModel;

class MenuController extends AbstractAdminController
{
	protected $adminMenuViewHelper;
	protected $adminMenuPagesViewHelper;

	public function indexAction()
	{
	    //Linguagem atual
	    $langCode = $this->params()->fromQuery(Language::QUERY_PARAM_LANG, 'pt');
	    $lang = $this->getRepository(Language::class)->find($langCode);

	    //Linguagens do site
        $languages = $this->getCurrentSite()->getLanguages();

		$pages = $this->getRepository(Post::class)->findBy([
			'type' => PostType::PAGE,
			'status' => PostStatus::PUBLISHED,
			'site' => $this->getSiteIdFromUri(),
            'language' => $langCode,
			'parent' => null
		], ['postDate'=>'ASC']);

		//Add page home
		$pageHome = new Post();
		$pageHome->setTitle('Home');
		$pageHome->setId('home');
		array_unshift($pages, $pageHome);

		$menu = $this->getRepository(Menu::class)->findOneBy([
		    'site'=>$this->getSiteIdFromUri(),
            'language' => $langCode
        ]);
		if(!$menu) {
			$menu = new Menu();
			$menu->setLanguage($lang);
			$menu->setSite($this->getCurrentSite());

			$this->getEntityManager()->persist($menu);
			$this->getEntityManager()->flush();
		}

		$request = $this->getRequest();
		if($request->isPost()) {
			foreach($menu->getItems() as $menuItem) {
				$this->getEntityManager()->remove($menuItem);
			}
			$menu->getItems()->clear();

			$dataMenu = $this->params()->fromPost('menu');
			$dataMenu = json_decode($dataMenu);
			$this->persistMenuItems($dataMenu, $menu);

			$this->getEntityManager()->persist($menu);
			$this->getEntityManager()->flush();

			$this->messages()->success('Menu atualizado com sucesso');
		}

		$this->getEntityManager()->refresh($menu);
		$menuItems = $menu->getItems()->toArray();

		return $this->getViewModel()->setVariables([
			'pages' => $pages,
			'site' => $this->getSiteIdFromUri(),
			'items' => $menuItems,
            'current_language' => $lang,
            'languages' => $languages
		]);
	}

	public function persistMenuItems($items, Menu &$menu, Item $itemParent=null)
	{
		$collItems = new ArrayCollection();

		foreach($items as $item) {

			$menuItem = new Item();
			$menuItem->setExternalUrl(isset($item->externalUrl) ? $item->externalUrl : null);
			$menuItem->setLabel(isset($item->label) ? $item->label : null);

			if(!empty($item->post)) {
				$page = $this->getRepository(Post::class)->find($item->post);
				$menuItem->setPost($page);
			}

			if(!empty($item->children)) {
				$children = $this->persistMenuItems($item->children, $menu, $menuItem);
				$menuItem->setChildren($children);
			}

			if($itemParent) {
				$menuItem->setParent($itemParent);
			} else {
				$menu->addItem($menuItem);
			}

			$collItems->add($menuItem);
		}

		return $collItems;
	}

	public function createItemAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		$adminMenuHelper = $this->getAdminMenuViewHelper();

		try {
			$itemType = $this->params()->fromPost('type');

			$markup = '';

			if($itemType == 'external') {
				$url = $this->params()->fromPost('url');
				$label = $this->params()->fromPost('label');

				$menuItem = new Item();
				$menuItem->setExternalUrl($url);
				$menuItem->setLabel($label);

				$markup = $adminMenuHelper->renderItem($menuItem);
			} else {
				$pages = $this->params()->fromPost('pages');
				foreach($pages as $id) {
					$menuItem = new Item();
					if($id == 'home') {
						$menuItem->setExternalUrl('#');
						$menuItem->setLabel('Home');
					} else {
						$page = $this->getRepository(Post::class)->findOneBy([
							'type' => PostType::PAGE,
							'id' => $id
						]);
						$menuItem->setPost($page);
					}

					$markup .= $adminMenuHelper->renderItem($menuItem);
				}
			}

			$jsonModel->node = $markup;
		} catch(\Exception $e) {
			$jsonModel->error = $e->getMessage();
		}

		return $jsonModel;
	}

	public function searchPageAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		$str = $this->params()->fromQuery('search');
		$language = $this->params()->fromQuery('language', 'pt');
		if(empty($str)) {
			return $jsonModel;
		}

		$posts = $this->getRepository(Post::class)->findByStr($str, $this->getSiteIdFromUri(), $language);

		$helper = $this->getAdminMenuPagesViewHelper();
		$markup = $helper($posts, true);
		$jsonModel->pages = $markup;

		return $jsonModel;
	}

	/**
	 * @return mixed
	 */
	public function getAdminMenuViewHelper()
	{
		return $this->adminMenuViewHelper;
	}

	/**
	 * @param mixed $adminMenuViewHelper
	 */
	public function setAdminMenuViewHelper($adminMenuViewHelper)
	{
		$this->adminMenuViewHelper = $adminMenuViewHelper;
	}

	/**
	 * @return mixed
	 */
	public function getAdminMenuPagesViewHelper()
	{
		return $this->adminMenuPagesViewHelper;
	}

	/**
	 * @param mixed $adminMenuPagesViewHelper
	 */
	public function setAdminMenuPagesViewHelper($adminMenuPagesViewHelper)
	{
		$this->adminMenuPagesViewHelper = $adminMenuPagesViewHelper;
	}
}