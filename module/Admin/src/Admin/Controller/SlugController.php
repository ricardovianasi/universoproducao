<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/12/2015
 * Time: 13:10
 */

namespace Admin\Controller;

use Application\Entity\Post\Post;
use Application\Entity\Post\PostType;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class SlugController extends AbstractAdminController
{
	public function indexAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		$siteId = $this->getSiteIdFromUri();

		if(!$this->getRequest()->isPost()) {
			$jsonModel->slug = false;
			return $jsonModel;
		}

		$str = $this->params()->fromPost('slug');
		if(!$str) {
			$jsonModel->slug = false;
			return $jsonModel;
		}

		$escapeId = $this->params()->fromPost('escape-id');

		$str = $this->slugify()->create($str, true, Post::class, $siteId, $escapeId);
		$jsonModel->slug = $str;
		return $jsonModel;
	}
}