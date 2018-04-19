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
use Application\Entity\Registration\Status;
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
            'canEdit' => $this->getAuthenticationService()->getIdentity()->getEmail() == 'brasilcinemundi@brasilcinemundi.com.br'?false:true
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
            'project' => $project,
            'canEdit' => $this->getAuthenticationService()->getIdentity()->getEmail() == 'brasilcinemundi@brasilcinemundi.com.br'?false:true
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

    public function exportAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $id = $this->params()->fromRoute('id');
        $item = $this->getRepository(Project::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);

        return $this->prepareReport($preparedItems, 'project' ,'pdf');
    }

    public function exportListAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(Project::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'project_list' ,'xlsx');
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
            unset($itemArray['peoples']);
            unset($itemArray['options']);
            unset($itemArray['instituition']);
            unset($itemArray['image']);

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
            if(!empty($itemArray['state_production'])) {
                $state = $itemArray['state_production'];
                $itemArray['state_production'] = $state->getName();
            }

            //category
            if(!empty( $itemArray['category'])) {
                $category = $itemArray['category'];
                $itemArray['category'] = $category->getName();
            }

            $itemArray['status'] = Status::get($obj->getStatus());

            //Peoples
            $producers = [];
            $directors = [];
            foreach ($obj->getPeoples() as $p) {
                if($p->getType() == People::TYPE_PRODUCER) {
                    $peopleData = [
                        'Nome: ' . $p->getName(),
                        'Endereço: ' . $p->getAddress(),
                        'Telefone: ' . $p->getPhone(),
                        'E-mail: ' . $p->getEmail(),
                        'Currículo: ' . $p->getDescription()
                    ];
                    $producers[] = implode(' | ', $peopleData);
                } elseif($p->getType() == People::TYPE_DIRECTOR) {
                    $peopleData = [
                        'Nome: ' . $p->getName(),
                        'Endereço: ' . $p->getAddress(),
                        'Telefone: ' . $p->getPhone(),
                        'E-mail: ' . $p->getEmail(),
                        'Biofilmografia: ' . $p->getDescription()
                    ];
                    $directors[] = implode(' | ', $peopleData);
                }
            }
            $itemArray['producers'] = implode(' ; ', $producers);
            $itemArray['directors'] = implode(' ; ', $producers);

            //instituition
            $itemArray['institution_social_name'] = $obj->getInstituition()->getSocialName();
            $itemArray['institution_fantasy_name'] = $obj->getInstituition()->getFantasyName();
            $itemArray['institution_cnpj'] = $obj->getInstituition()->getCnpj();
            $itemArray['institution_address'] = $obj->getInstituition()->getAddress();
            $itemArray['institution_legal_representative'] = $obj->getInstituition()->getLegalRepresentative();
            $itemArray['institution_phone'] = $obj->getInstituition()->getPhone();
            $itemArray['institution_mobile_phone'] = $obj->getInstituition()->getMobilePhone();
            $itemArray['institution_site'] = $obj->getInstituition()->getSite();
            $itemArray['institution_email'] = $obj->getInstituition()->getEmail();
            $itemArray['institution_description'] = $obj->getInstituition()->getDescription();

            //Options
            unset($itemArray['options']);
            $opt_phase = "";
            if($opt_phase = $obj->getOption('phase')) {
                $opt_phase = $opt_phase->getLabel();
            }
            $itemArray['opt_phase'] = $opt_phase?$opt_phase:"";

            $opt_category = "";
            if($opt_category = $obj->getOption('category')) {
                $opt_category = $opt_category->getLabel();
            }
            $itemArray['opt_category'] = $opt_category?$opt_category:"";

            $opt_genre = "";
            if($opt_genre = $obj->getOption('genre')) {
                $opt_genre = $opt_genre->getLabel();
            }
            $itemArray['opt_genre'] = $opt_genre?$opt_genre:"";

            $opt_format = "";
            if($opt_format = $obj->getOption('format')) {
                $opt_format = $opt_format->getLabel();
            }
            $itemArray['opt_format'] = $opt_format?$opt_format:"";

            $opt_display_format = "";
            if($opt_display_format = $obj->getOption('display_format')) {
                $opt_display_format = $opt_display_format->getLabel();
            }
            $itemArray['opt_display_format'] = $opt_display_format?$opt_display_format:"";

            $opt_written_script = "";
            if($opt_written_script = $obj->getOption('written_script')) {
                $opt_written_script = $opt_written_script->getLabel();
            }
            $itemArray['opt_written_script'] = $opt_written_script?$opt_written_script:"";

            $opt_first_or_second_project = "";
            if($opt_first_or_second_project = $obj->getOption('first_or_second_project')) {
                $opt_first_or_second_project = $opt_first_or_second_project->getLabel();
            }
            $itemArray['opt_first_or_second_project'] = $opt_first_or_second_project?$opt_first_or_second_project:"";

            //Duration
            $duration = "";
            if($obj->getMovieLength() instanceof \DateTime) {
                $duration = $obj->getMovieLength()->format('H:i:s');
            }
            $itemArray['movie_length'] = $duration;

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