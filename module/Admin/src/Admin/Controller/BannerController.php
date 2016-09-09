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
            $banner->setSite($this->getCurrentSite());
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
        $banner = $this->getRepository(Banner::class)->find($id);
        $this->getEntityManager()->remove($banner);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Banner excluído com sucesso.');

        return $this->redirect()->toRoute('admin/banner', ['site' => $this->getSiteIdFromUri()]);
    }
}