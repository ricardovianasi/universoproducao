<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;

use Admin\Form\ExternalUser\UserSubCategoryForm;
use Application\Entity\User\Category;

class UserSubCategoryController extends AbstractAdminController
    implements CrudInterface
{
    public function indexAction()
    {
        $form = new UserSubCategoryForm($this->getEntityManager());
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

                $sub = $this->getRepository(Category::class)->find($data['parent']);

                $category->setName($data['name']);
                $category->setParent($sub);

                $this->getEntityManager()->persist($category);
                $this->getEntityManager()->flush();
                if($this->params()->fromRoute('id')) {
                    $this->messages()->flashSuccess("Categoria atualizada com sucesso!");
                    return $this->redirect()->toRoute('admin/default', ['controller'=>'user-sub-category']);
                } else {
                    $this->messages()->success("Categoria criada com sucesso!");
                }
            }

            $form->get('name')->setValue('');
//            $form->get('id')->setValue('');

        } elseif($id = $this->params()->fromRoute('id')) {
            $category = $this
                ->getRepository(Category::class)
                ->find($id);

            $form->setData($category->toArray());
        }

        //$items = $this->getRepository(Category::class)->findBy(['parent'=>null], ['name'=>'ASC']);
        $qb = $this->getEntityManager()->createQueryBuilder();
        $items = $qb
            ->from(Category::class, 'p')
            ->select('p')
            ->where($qb->expr()->isNotNull('p.parent'))
            ->getQuery()
            ->getResult();

        $this->getViewModel()->setVariables([
            'items' => $items,
            'form' => $form
        ]);

        return $this->getViewModel();
    }

    public function deleteAction($id=null)
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

    public function createAction($data)
    {
        // TODO: Implement createAction() method.
    }

    public function updateAction($id, $data)
    {
        // TODO: Implement updateAction() method.
    }

    public function persist($data, $id = null)
    {
        // TODO: Implement persist() method.
    }
}