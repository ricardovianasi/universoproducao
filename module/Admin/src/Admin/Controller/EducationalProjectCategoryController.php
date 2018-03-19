<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;


use Admin\Form\EducationalProject\CategoryForm;
use Application\Entity\EducationalProject\Category;
use Application\Entity\Registration\Registration;

class EducationalProjectCategoryController extends AbstractAdminController
{
    public function indexAction()
    {
        $searchForm = new CategoryForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(Category::class, $data);

        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm,
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
        $result->setTemplate('admin/educational-project-category/create.phtml');
        return $result;
    }

    public function deleteAction($id)
    {
        $cat = $this->getRepository(Category::class)->find($id);
        $this->getEntityManager()->remove($cat);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Categoria excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'educational-project-category']);
    }

    public function persist($data, $id = null)
    {
        $form = new CategoryForm($this->getEntityManager($this->getEntityManager()));

        if($id) {
            $category = $this->getRepository(Category::class)->find($id);
        } else {
            $category = new Category();
        }

        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if($form->isValid()) {
                $dataValida = $form->getData();

                if(!empty($dataValida['registration'])) {
                    $reg = $this->getRepository(Registration::class)->find($dataValida['registration']);
                    $category->setRegistration($reg);
                }
                unset($dataValida['registration']);

                $category->setData($dataValida);
                $this->getEntityManager()->persist($category);
                $this->getEntityManager()->flush();

                if($id) {
                    $this->messages()->success("Categoria atualizada com sucesso!");
                } else {
                    $this->messages()->flashSuccess("Categoria criado com sucesso!");
                    return $this->redirect()->toRoute('admin/default', [
                        'controller' => 'educational-project-category',
                        'action' => 'update',
                        'id' => $category->getId()
                    ]);
                }
            }
        } else {
            $form->setData($category->toArray());
        }

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'category' => $category
        ]);
    }

}