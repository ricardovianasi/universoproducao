<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;

use Admin\Form\ExternalUser\UserCategoryForm;
use Application\Entity\User\Category;

class UserCategoryController extends AbstractAdminController
{
    public function indexAction()
    {
        $form = new UserCategoryForm();
        if($this->getRequest()->isPost()) {
            $data  = $this->getRequest()->getPost();
            $form->setData($data);
            if($form->isValid()) {
                if($this->params()->fromRoute('id')) {
                    $category = $this
                        ->getRepository(Category::class)
                        ->find($this->params()->fromRoute('id'));
                } else {
                    $category = new Category();
                }

                $category->setData($form->getData());

                $this->getEntityManager()->persist($category);
                $this->getEntityManager()->flush();
                if($this->params()->fromRoute('id')) {
                    $this->messages()->flashSuccess("Categoria atualizada com sucesso!");
                    return $this->redirect()->toRoute('admin/default', ['controller'=>'user-category']);
                } else {
                    $this->messages()->flashSuccess("Categoria criada com sucesso!");
                    return $this->redirect()->toRoute('admin/default', ['controller'=>'user-category']);
                }

                $form->setData([]);
            }
        } elseif($id = $this->params()->fromRoute('id')) {
            $category = $this
                ->getRepository(Category::class)
                ->find($id);

            $form->setData($category->toArray());
        }

        $items = $this->getRepository(Category::class)->findBy(['parent'=>null], ['name'=>'ASC']);
        $this->getViewModel()->setVariables([
            'items' => $items,
            'form' => $form
        ]);

        return $this->getViewModel();
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $category = $this
            ->getRepository(Category::class)
            ->find($id);

        if($category) {
            $this->getEntityManager()->remove($category);
            $this->getEntityManager()->flush();

            $this->messages()->flashSuccess('Categoria excluída com sucesso.');
        } else {
            $this->messages()->flashError('Erro ao localizar categoria. Por favor, tente novamente.');
        }

        return $this->redirect()->toRoute('admin/default', ['controller'=>'art-category']);
    }

}