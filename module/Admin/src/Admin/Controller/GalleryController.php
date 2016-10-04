<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 11/03/2016
 * Time: 15:37
 */

namespace Admin\Controller;

use Application\Entity\Banner\Banner;
use Application\Entity\Gallery\Gallery;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostMeta;
use Application\Entity\Post\PostType;
use Application\Entity\Site\Menu\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Model\JsonModel;

class GalleryController extends AbstractAdminController
{
	protected $adminGalleryHelper;

	public function indexAction()
	{
		$items = $this->getRepository(Post::class)->findBy([
		    'type' => PostType::GALLERY,
			'site' => $this->getSiteIdFromUri(),
		], ['order'=>'ASC']);

		$request = $this->getRequest();
		if($request->isPost()) {
			foreach ($items as $item) {
				$this->getEntityManager()->remove($item);
			}

			$items = new ArrayCollection();
			$order = 1;
			$data = $this->processBodyContent($this->getRequest());

			if(empty($data['gallery'])) {
				$data['gallery'] = [];
			}

			foreach ($data['gallery'] as $item) {
				$galleryItem = new Post();
				$galleryItem->setType(PostType::GALLERY);
                $galleryItem->setAuthor($this->getAuthenticationService()->getIdentity());
                $galleryItem->setTitle($item['title']);
                $galleryItem->setContent($item['description']);
                $galleryItem->addMeta(new PostMeta(PostMeta::IMAGE, $item['file']));
                if($item['credits']) {
                    $galleryItem->addMeta(new PostMeta(PostMeta::CREDITS, $item['credits']));
                }
				$galleryItem->setOrder($order++);
				$galleryItem->setSite($this->getCurrentSite());

				$this->getEntityManager()->persist($galleryItem);
				$items->add($galleryItem);
			}

			$this->getEntityManager()->flush();
			$this->messages()->success('Galeria atualizada com sucesso!');
		}

		//prepare items
        $prepareItems = [];
        foreach ($items as $item) {
            $prepareItems[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'description' => $item->getContent(),
                'image' => $item->getMeta(PostMeta::IMAGE),
                'credits' => $item->getMeta(PostMeta::CREDITS),
            ];
        }

		return $this->getViewModel()->setVariables([
			'site' => $this->getSiteIdFromUri(),
			'items' => $prepareItems
		]);
	}

	public function addItemAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		$adminGalleryHelper = $this->getAdminGalleryHelper();

		try {
			$media = $this->params()->fromPost('media');
			$item = [
                'id' => time(),
                'title' => '',
                'description' => '',
                'image' => $media,
                'credits' => ''
            ];
			$markup = $adminGalleryHelper->renderRow($item);
			$jsonModel->item = $markup;
		} catch(\Exception $e) {
			$jsonModel->error = $e->getMessage();
		}

		return $jsonModel;

	}

	/**
	 * @return mixed
	 */
	public function getAdminGalleryHelper()
	{
		return $this->adminGalleryHelper;
	}

	/**
	 * @param mixed $adminGalleryHelper
	 */
	public function setAdminGalleryHelper($adminGalleryHelper)
	{
		$this->adminGalleryHelper = $adminGalleryHelper;
	}
}