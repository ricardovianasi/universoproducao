<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;

use Admin\Form\Art\ArtCategoryForm;
use Admin\Form\Seminar\SeminarCategoryForm;
use Admin\Form\Seminar\SeminarThematicForm;
use Application\Entity\Art\Category;
use Application\Entity\Seminar\Thematic;

class SeminarCategoryController extends AbstractAdminController
{
    public function indexAction()
    {
        $form = new SeminarCategoryForm();
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
                    return $this->redirect()->toRoute('admin/default', ['controller'=>'seminar-category']);
                } else {
                    $this->messages()->success("Categoria criada com sucesso!");
                }
            }
        } elseif($id = $this->params()->fromRoute('id')) {
            $category = $this
                ->getRepository(Category::class)
                ->find($id);

            $form->setData($category->toArray());
        }

        $items = $this->getRepository(Category::class)->findBy([], ['name'=>'ASC']);
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

        return $this->redirect()->toRoute('admin/default', ['controller'=>'seminar-category']);
    }

}