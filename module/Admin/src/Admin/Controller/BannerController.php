<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 11/03/2016
 * Time: 15:37
 */

namespace Admin\Controller;


use Admin\Form\BannerForm;
use Application\Entity\Banner\Banner;
use Zend\View\Model\JsonModel;

class BannerController extends AbstractAdminController implements CrudInterface
{

    public function indexAction()
    {
        $items = $this->search(Banner::class, ['site' => $this->getSiteIdFromUri()]);
        $this->getViewModel()->setVariables([
            'items' => $items,
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
        $result->setTemplate('admin/banner/create.phtml');
        return $result;
    }

    public function persist($data, $id = null)
    {
        $form = new BannerForm();
        if($id) {
            $banner = $this->getRepository(Banner::class)->find($id);
        } else {
            $banner = new Banner();
        }

        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if($form->isValid()) {
                $validData = $form->getData();

                $banner->setData($this->prepareDataPost(Banner::class, $validData));

                $this->getEntityManager()->persist($banner);
                $this->getEntityManager()->flush();

                if($id) {
                    $this->messages()->success("Banner atualizado com sucesso!");
                } else {
                    $this->messages()->flashSuccess("Banner criado com sucesso!");
                    return $this->redirect()->toRoute('admin/banner', [
                        'action' => 'update',
                        'id' => $banner->getId(),
                        'site' => $this->getSiteIdFromUri()
                    ]);
                }
            }
        } else {
            $form->setData($banner->toArray());
        }

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'banner' => $banner,
            'site' => $this->getSiteIdFromUri()
        ]);

    }

    public function deleteAction($id)
    {
    }

    /*protected $adminBannerHelper;
       /*public function indexAction()
    {
        $items = $this->getRepository(Banner::class)->findBy([
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

            if(empty($data['banner'])) {
                $data['banner'] = [];
            }

            foreach ($data['banner'] as $item) {
                $bannerItem = new Banner();
                $bannerItem->setData($item);
                $bannerItem->setOrder($order++);
                $bannerItem->setSite($this->getCurrentSite());

                $this->getEntityManager()->persist($bannerItem);
                $items->add($bannerItem);
            }

            $this->getEntityManager()->flush();
            $this->messages()->success('Banner atualizado com sucesso');
        }

        return $this->getViewModel()->setVariables([
            'site' => $this->getSiteIdFromUri(),
            'items' => $items
        ]);
    }*/

	public function addItemAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		$adminbannerHelper = $this->getAdminBannerHelper();

		try {
			$media = $this->params()->fromPost('media');

			$banner = new Banner();
			$banner->setId(time());
			$banner->setFile($media);

			$markup = $adminbannerHelper->renderRow($banner);
			$jsonModel->item = $markup;
		} catch(\Exception $e) {
			$jsonModel->error = $e->getMessage();
		}

		return $jsonModel;

	}

	/**
	 * @return mixed
	 */
	public function getAdminBannerHelper()
	{
		return $this->adminBannerHelper;
	}

	/**
	 * @param mixed $adminBannerHelper
	 */
	public function setAdminBannerHelper($adminBannerHelper)
	{
		$this->adminBannerHelper = $adminBannerHelper;
	}
}