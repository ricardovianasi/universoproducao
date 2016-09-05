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
use Application\Entity\Site\Menu\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Model\JsonModel;

class GalleryController extends AbstractAdminController
{
	protected $adminGalleryHelper;

	public function indexAction()
	{
		$items = $this->getRepository(Gallery::class)->findBy([
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
				$galleryItem = new Gallery();
				$galleryItem->setData($item);
				$galleryItem->setOrder($order++);
				$galleryItem->setSite($this->getCurrentSite());

				$this->getEntityManager()->persist($galleryItem);
				$items->add($galleryItem);
			}

			$this->getEntityManager()->flush();
			$this->messages()->success('Galeria atualizada com sucesso!');
		}

		return $this->getViewModel()->setVariables([
			'site' => $this->getSiteIdFromUri(),
			'items' => $items
		]);
	}

	public function addItemAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		$adminGalleryHelper = $this->getAdminGalleryHelper();

		try {
			$media = $this->params()->fromPost('media');

			$gallery = new Gallery();
			$gallery->setId(time());
			$gallery->setFile($media);

			$markup = $adminGalleryHelper->renderRow($gallery);
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