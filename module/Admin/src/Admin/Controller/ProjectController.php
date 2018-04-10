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
use Application\Entity\File\File;
use Application\Entity\Project\People;
use Application\Entity\Project\Project;
use Doctrine\Common\Collections\ArrayCollection;

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

            $data = array_replace_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $form->setData($data);
            if($form->isValid()) {
                $dataValida = $form->getData();

                //options
                $options = new ArrayCollection();
                if(!empty($dataValida['options'])) {
                    foreach ($dataValida['options'] as $opt) {
                        if(!empty($opt)) {
                            if(is_string($opt)) {
                                $optEntity = $this->getRepository(Options::class)->find($opt);
                                if($optEntity) {
                                    $options->add($optEntity);
                                }
                            } elseif(is_array($opt)) {
                                foreach ($opt as $oId) {
                                    $optEntity = $this->getRepository(Options::class)->find($oId);
                                    if($optEntity) {
                                        $options->add($optEntity);
                                    }
                                }
                            }
                        }
                    }
                }
                $project->setOptions($options);
                unset($dataValida['options']);

                //estado
                if(!empty($dataValida['state_production'])) {
                    $state = $this
                        ->getRepository(State::class)
                        ->find($dataValida['state_production']);

                    $project->setStateProduction($state);
                }
                unset($dataValida['state_production']);

                $newPeoples = new ArrayCollection();
                $oldPeoples = [];
                foreach ($project->getPeoples() as $p) {
                    $oldPeoples[$p->getId()] = $p;
                }

                //produtores
                if(!empty($dataValida['producers'])) {
                    foreach ($dataValida['producers'] as $prod) {
                        $productor = $this->populatePeople($prod, People::TYPE_PRODUCER);
                        $productor->setProject($project);
                        $newPeoples->add($productor);
                        if($productor->getId()) {
                            unset($oldPeoples[$productor->getId()]);
                        }
                    }
                }
                unset($dataValida['producers']);

                //diretores
                if(!empty($dataValida['directors'])) {
                    foreach ($dataValida['directors'] as $dir) {
                        $director = $this->populatePeople($dir, People::TYPE_DIRECTOR);
                        $director->setProject($project);
                        $newPeoples->add($director);
                        if($director->getId()) {
                            unset($oldPeoples[$director->getId()]);
                        }
                    }
                }
                unset($dataValida['directors']);

                $project->setPeoples($newPeoples);
                foreach ($oldPeoples as $oldP) {
                    $this->fileManipulation()->removeFile($oldP->getImage());
                    $this->getEntityManager()->remove($oldP);
                }

                //Tempo de duração
                if(!empty($dataValida['movie_length_hour'] && !empty($dataValida['movie_length_minutes']))) {
                    $time = new \DateTime();
                    $time->setTime($dataValida['movie_length_hour'], $dataValida['movie_length_minutes']);
                    $project->setMovieLength($time);
                }
                unset($dataValida['movie_length_hour']);
                unset($dataValida['movie_length_minutes']);

                if(!empty($dataValida['instituition'])) {
                    $instituition = new Institution();
                    $instituition->setData($dataValida['instituition']);
                    $project->setInstituition($instituition);
                }
                unset($dataValida['instituition']);


                if(!empty($dataValida['image'])) {
                    $image = $this->populateFiles($dataValida['image'], true);
                    $project->setImage($image);
                }

                //Files
                $files = new ArrayCollection();
                if(!empty($dataValida['files'])) {
                    foreach ($dataValida['files'] as $f) {
                        $file = $this->populateFiles($f);
                        $files->add($file);
                    }
                }
                unset($dataValida['image']);
                unset($dataValida['files']);
                $project->setFiles($files);

                $project->setData($dataValida);

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

    protected function populatePeople($data, $type)
    {
        if(!empty($data['id'])) {
            $people = $this->getRepository(People::class)->find($data['id']);
        } else {
            $people = new People();
        }

        $image = "";
        if(!empty($data['image']['name'])) {
            $file = $this->fileManipulation()->moveToRepository($data['image']);
            $image = $file['new_name'];

            if(!empty($people->getImage())) {
                $this->fileManipulation()->removeFile($people->getImage());
            }
        } else {
            $image = $data['image'];
        }
        unset($data['image']);

        $people->setImage($image);
        $people->setData($data);
        $people->setType($type);

        return $people;
    }

    protected function populateFiles($data, $isDefault=false)
    {
        $media = new File();
        $media->setIsDefault($isDefault);

        if(!empty($data['file'])) {
            $mediaFile = $data["file"];
            if(!empty($mediaFile['name'])) {
                $file = $this->fileManipulation()->moveToRepository($mediaFile);
                $media->setSrc($file['new_name']);
            }
        }

        return $media;
    }

}