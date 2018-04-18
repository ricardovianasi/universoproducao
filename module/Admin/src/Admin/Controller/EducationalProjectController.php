<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;

use Admin\Form\EducationalProject\EducationalProjectForm;
use Application\Entity\EducationalProject\Category;
use Application\Entity\EducationalProject\EducationalProject;
use Application\Entity\File\File;
use Application\Entity\Registration\Status;
use Application\Entity\State;
use Doctrine\Common\Collections\ArrayCollection;

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
        return $this->persist($data, $id);
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

                if(!empty($data['category'])) {
                    $cat = $this->getRepository(Category::class)->find($data['category']);
                    $project->setCategory($cat);
                }
                unset($data['category']);

                $oldFiles = [];
                foreach ($project->getFiles() as $of) {
                    $oldFiles[$of->getId()] = $of;
                }
                $files = new ArrayCollection();
                if(!empty($data['files'])) {
                    foreach ($data['files'] as $f) {
                        $file = $this->populateFiles($f);
                        if($file) {
                            $files->add($file);
                            unset($oldFiles[$file->getId()]);
                        }
                    }
                }
                foreach ($oldFiles as $key=>$fileToRemove) {
                    $this->getEntityManager()->remove($fileToRemove);
                }
                unset($data['image']);
                unset($data['files']);
                $project->setFiles($files);

                $project->setData($data);
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
        }

        $form->setData($project->toArray());

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'project' => $project
        ]);
    }

    protected function populateFiles($data, $isDefault=false)
    {
        $media = null;
        if(!empty($data['id'])) {
            $media = $this->getRepository(File::class)->find($data['id']);
        }

        if(!$media) {
            $media = new File();
        }

        if(!empty($data['file']['name'])) {
            $mediaFile = $data["file"];
            if(!empty($mediaFile['name'])) {
                $file = $this->fileManipulation()->moveToRepository($mediaFile);
                $media->setSrc($file['new_name']);
            }
        } elseif(empty($media->getId())) {
            return;
        }

        return $media;
    }

    public function exportAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $id = $this->params()->fromRoute('id');
        $item = $this->getRepository(EducationalProject::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);

        return $this->prepareReport($preparedItems, 'educational_project' ,'pdf');
    }

    public function exportListAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(EducationalProject::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'educational_project_list' ,'xlsx');
    }

    protected function prepareItemsForReports($items)
    {
        if(!is_array($items)) {
            $items = [$items];
        }

        $preparedItems = [];
        foreach ($items as $obj) {

            $itemArray = $obj->toArray();
            unset($itemArray['medias']);
            unset($itemArray['updated_at']);
            unset($itemArray['registration']);
            unset($itemArray['default_input_filters']);
            unset($itemArray['event']);
            unset($itemArray['files']);

            //Author
            $author = [
                'user_id' => $obj->getUser()->getId(),
                'user_name' => $obj->getUser()->getName(),
                'user_email' => $obj->getUser()->getEmail(),
                'user_address' => $obj->getUser()->getFullAddress()
            ];
            $phones = [];
            foreach ($obj->getUser()->getPhones() as $phone) {
                $phones[] = implode('|', $phone->_toArray());
            }
            $author['user_phones'] = implode(';', $phones);

            $itemArray = $itemArray+$author;
            unset($itemArray['user']);

            //Event
            $itemArray['event_name'] = $obj->getEvent()->getFullName();

            //state
            if(!empty($itemArray['state']) && $itemArray['state'] instanceof State) {
                $state = $itemArray['state'];
                $itemArray['state'] = $state->getName();
            }

            //category
            if(!empty( $itemArray['category']) &&  $itemArray['category'] instanceof Category) {
                $category = $itemArray['category'];
                $itemArray['category'] = $category->getName();
            }

            $itemArray['status'] = Status::get($obj->getStatus());

            //Created At
            $createdAt = "";
            if($obj->getCreatedAt() instanceof \DateTime) {
                $createdAt = $obj->getCreatedAt()->format('d/m/Y H:i:s');
            }
            $itemArray['created_at'] = $createdAt;

            $preparedItems[] = ['object'=>$itemArray];
        }
        return $preparedItems;
    }
}