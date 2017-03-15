<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 11/03/2016
 * Time: 15:37
 */

namespace Admin\Controller;


use Admin\Form\BannerForm;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostType;

class BannerController extends AbstractAdminController implements CrudInterface
{

    public function indexAction()
    {
        $items = $this->search(Post::class, ['site' => $this->getSiteIdFromUri(), 'type'=>PostType::BANNER]);
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
            $banner = $this->getRepository(Post::class)->find($id);
        } else {
            $banner = new Post();
            $banner->setSite($this->getCurrentSite());
            $banner->setType(PostType::BANNER);
            $banner->setAuthor($this->getAuthenticationService()->getIdentity());
        }

        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if($form->isValid()) {
                $banner->setData($this->prepareDataPost(Post::class, $data, $banner));
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
        $banner = $this->getRepository(Post::class)->find($id);
        $this->getEntityManager()->remove($banner);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Banner excluído com sucesso.');

        return $this->redirect()->toRoute('admin/banner', ['site' => $this->getSiteIdFromUri()]);
    }
}