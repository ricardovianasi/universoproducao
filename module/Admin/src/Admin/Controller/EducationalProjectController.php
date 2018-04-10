<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;


use Admin\Form\EducationalProject\CategoryForm;
use Admin\Form\EducationalProject\EducationalProjectForm;
use Application\Entity\EducationalProject\Category;
use Application\Entity\EducationalProject\EducationalProject;
use Application\Entity\Registration\Registration;

class EducationalProjectController extends AbstractAdminController
{
    public function indexAction()
    {
        $searchForm = new EducationalProjectForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(EducationalProject::class, $data);

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
        $result->setTemplate('admin/educational-project/create.phtml');
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
        $form = new EducationalProjectForm($this->getEntityManager($this->getEntityManager()));

        if($id) {
            $project = $this->getRepository(EducationalProject::class)->find($id);
        } else {
            $project = new EducationalProject();
        }

        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if($form->isValid()) {
                $dataValida = $form->getData();


                $project->setData($dataValida);
                $this->getEntityManager()->persist($project);
                $this->getEntityManager()->flush();

                if($id) {
                    $this->messages()->success("Projeto atualizado com sucesso!");
                } else {
                    $this->messages()->flashSuccess("Projeto criado com sucesso!");
                    return $this->redirect()->toRoute('admin/default', [
                        'controller' => 'educational-project',
                        'action' => 'update',
                        'id' => $project->getId()
                    ]);
                }
            }
        } else {
            $form->setData($project->toArray());
        }

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'project' => $project
        ]);
    }

}