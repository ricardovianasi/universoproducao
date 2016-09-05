<?php
namespace Admin\Controller;

use Application\Entity\Post\PostSite;
use Application\Entity\Site\Site;
use Zend\View\Model\JsonModel;

class SiteController extends AbstractAdminController
{
	protected $adminPostSiteViewHelper;

	public function addSiteAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);
		$helper = $this->getAdminPostSiteViewHelper();

		if($this->getRequest()->isPost()) {
			$siteId = $this->params()->fromPost('site');

			$postSite = new PostSite();

			if($siteId == 'all') {
				$site = new Site();
				$site->setId('all');
				$site->setName('Todos');
			} else {
				$site = $this->getRepository(Site::class)->find($siteId);
			}

			$postSite->setId(time());
			$postSite->setSite($site);
			$postSite->setHighlight($this->params()->fromPost('highlight', false));

			$jsonModel->postSite = $helper->renderRow($postSite);
		}

		return $jsonModel;
	}

	/**
	 * @return mixed
	 */
	public function getAdminPostSiteViewHelper()
	{
		return $this->adminPostSiteViewHelper;
	}

	/**
	 * @param mixed $adminPostSiteViewHelper
	 */
	public function setAdminPostSiteViewHelper($adminPostSiteViewHelper)
	{
		$this->adminPostSiteViewHelper = $adminPostSiteViewHelper;
	}

	/*public function getViewManager()
	{
		return $this->getServiceLocator()->get('ViewHelperManager');
	}*/
}