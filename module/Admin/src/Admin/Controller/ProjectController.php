<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;


use Admin\Form\Project\ProjectForm;
use Admin\Form\Project\ProjectSearchForm;
use Application\Entity\Project\Project;

class ProjectController extends AbstractAdminController
{
    public function indexAction()
    {
        $searchForm = new ProjectSearchForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(Project::class, $data);

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
        $result->setTemplate('admin/project/create.phtml');
        return $result;
    }

    public function deleteAction($id)
    {
        $project = $this->getRepository(Project::class)->find($id);
        $this->getEntityManager()->remove($project);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Projeto excluído com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'project']);
    }

    public function persist($data, $id = null)
    {
        $form = new ProjectForm($this->getEntityManager($this->getEntityManager()));

        if($id) {
            $project = $this->getRepository(Project::class)->find($id);
        } else {
            $project = new Project();
        }

        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if($form->isValid()) {
                $dataValida = $form->getData();


                $project->setData($dataValida);
                $this->getEntityManager()->persist($project);
                $this->getEntityManager()->flush();

                if($id) {
                    $this->messages()->success("Categoria atualizada com sucesso!");
                } else {
                    $this->messages()->flashSuccess("Categoria criado com sucesso!");
                    return $this->redirect()->toRoute('admin/default', [
                        'controller' => 'educational-project-category',
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