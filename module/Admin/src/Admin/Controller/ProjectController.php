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
use Admin\Form\Project\StatusModalForm;
use Application\Entity\File\File;
use Application\Entity\Institution\Institution;
use Application\Entity\Project\Options;
use Application\Entity\Project\People;
use Application\Entity\Project\Project;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\State;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;

class ProjectController extends AbstractAdminController
{
    public function indexAction()
    {
        $statusModalForm = new StatusModalForm();

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
            'searchData' => $dataAttr,
            'isFiltered' => !empty($data) ? true : false,
            'statusModalForm' => $statusModalForm,
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
        $form->getInputFilter()->remove('image');
        $form->getInputFilter()->remove('files');
        $form->getInputFilter()->remove('directors');
        $form->getInputFilter()->remove('producers');

        if($id) {
            $project = $this->getRepository(Project::class)->find($id);
        } else {
            $project = new Project();
        }

        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if($form->isValid()) {

                //Author
                $user = null;
                if(!empty($data['user'])) {
                    $user = $this
                        ->getRepository(User::class)
                        ->find($data['user']);
                }
                $project->setUser($user);
                unset($data['user']);

                $registration = null;
                if(!empty($data['registration'])) {
                    $registration = $this
                        ->getRepository(Registration::class)
                        ->find($data['registration']);
                }
                $project->setRegistration($registration);
                $project->setEvent($registration->getEvent());
                unset($data['registration']);

                //options
                $options = new ArrayCollection();
                if(!empty($data['options'])) {
                    foreach ($data['options'] as $opt) {
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
                unset($data['options']);

                //estado
                if(!empty($data['state_production'])) {
                    $state = $this
                        ->getRepository(State::class)
                        ->find($data['state_production']);

                    $project->setStateProduction($state);
                }
                unset($data['state_production']);

                $newPeoples = new ArrayCollection();
                $oldPeoples = [];
                foreach ($project->getPeoples() as $p) {
                    $oldPeoples[$p->getId()] = $p;
                }

                //produtores
                if(!empty($data['producers'])) {
                    foreach ($data['producers'] as $prod) {
                        $productor = $this->populatePeople($prod, People::TYPE_PRODUCER);
                        $productor->setProject($project);
                        $newPeoples->add($productor);
                        if($productor->getId()) {
                            unset($oldPeoples[$productor->getId()]);
                        }
                    }
                }
                unset($data['producers']);

                //diretores
                if(!empty($data['directors'])) {
                    foreach ($data['directors'] as $dir) {
                        $director = $this->populatePeople($dir, People::TYPE_DIRECTOR);
                        $director->setProject($project);
                        $newPeoples->add($director);
                        if($director->getId()) {
                            unset($oldPeoples[$director->getId()]);
                        }
                    }
                }
                unset($data['directors']);

                $project->setPeoples($newPeoples);
                foreach ($oldPeoples as $oldP) {
                    $this->fileManipulation()->removeFile($oldP->getImage());
                    $this->getEntityManager()->remove($oldP);
                }

                //Tempo de duração
                if(!empty($data['movie_length_hour'] && !empty($data['movie_length_minutes']))) {
                    $time = new \DateTime();
                    $time->setTime($data['movie_length_hour'], $data['movie_length_minutes']);
                    $project->setMovieLength($time);
                }
                unset($data['movie_length_hour']);
                unset($data['movie_length_minutes']);

                if(!empty($data['instituition'])) {
                    $instituition = new Institution();
                    $instituition->setData($data['instituition']);
                    $project->setInstituition($instituition);
                }
                unset($data['instituition']);


                if(!empty($data['image']['file']['name'])) {
                    $image = $this->populateFiles($data['image'], true);
                    if($image) {
                        $project->setImage($image);
                    }
                }
                unset($data['image']);

                //Files
                $files = new ArrayCollection();
                if(!empty($data['files'])) {
                    foreach ($data['files'] as $f) {
                        if(!empty($f['file']['name'])) {
                            $file = $this->populateFiles($f);
                            $files->add($file);
                        }
                    }
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
                        'controller' => 'project',
                        'action' => 'update',
                        'id' => $project->getId()
                    ]);
                }
            }
        }

        $form->setData($project->toArray());

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
        } elseif($people->getImage()) {
            $image = $people->getImage();
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
            $producers = "";
            $directors = "";
            foreach ($obj->getPeoples() as $p) {
                if($p->getType() == People::TYPE_PRODUCER) {
                    /*$peopleData = [
                        'Nome: ' . $p->getName(),
                        'Endereço: ' . $p->getAddress(),
                        'Telefone: ' . $p->getPhone(),
                        'E-mail: ' . $p->getEmail(),
                        'Currículo: ' . $p->getDescription()
                    ];
                    $producers[] = implode(' | ', $peopleData);*/
                    $producers.= $this->preparePeoplesToReport($p);
                } elseif($p->getType() == People::TYPE_DIRECTOR) {
                    /*$peopleData = [
                        'Nome: ' . $p->getName(),
                        'Endereço: ' . $p->getAddress(),
                        'Telefone: ' . $p->getPhone(),
                        'E-mail: ' . $p->getEmail(),
                        'Biofilmografia: ' . $p->getDescription()
                    ];
                    $directors[] = implode(' | ', $peopleData);*/
                    $directors.= $this->preparePeoplesToReport($p);
                }
            }
            /*$itemArray['producers'] = implode(' ; ', $producers);
            $itemArray['directors'] = implode(' ; ', $producers);*/
            $itemArray['producers'] = $producers;
            $itemArray['directors'] = $directors;

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

    protected function preparePeoplesToReport(People $people)
    {
        $labelDescription = $people->getType()==People::TYPE_PRODUCER ? 'Currículo' : 'Biofilmografia';

        $txt = "";
        $txt.= "<b>Nome</b>: " . $people->getName() . "<br />";
        $txt.= "<b>Endereço</b>: " . $people->getAddress() . "<br />";
        $txt.= "<b>Telefone</b>: " . $people->getPhone() . "<br />";
        $txt.= "<b>$labelDescription</b>: " . nl2br($people->getDescription()) . "<br /><br /><br />";

        return $txt;
    }

    public function statusAction()
    {
        if(!$this->getRequest()->isPost()) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'project',
                'action' => 'index'
            ]);
        }

        $data = $this->getRequest()->getPost()->toArray();
        if(empty($data['status'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'project',
                'action' => 'index'
            ]);
        }

        if(empty($data['filter'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'project',
                'action' => 'index'
            ]);
        }

        $status = $data['status'];
        parse_str(urldecode($data['filter']), $filter);

        if(empty($filter['selected'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'project',
                'action' => 'index'
            ]);
        }

        $selectedItens = [];
        if($filter['selected'] == 'all') {
            $selectedItens = $this->search(Project::class, $filter, [], true);
        } else {
            $selected = explode(',', $filter['selected']);
            if(!$selected) {
                $this->messages()->flashError("Erro ao processar solicitação.");
                return $this->redirect()->toRoute('admin/default', [
                    'controller' => 'project',
                    'action' => 'index'
                ]);
            }

            $qb = $this
                ->getRepository(Project::class)
                ->createQueryBuilder('m');

            $selectedItens = $qb
                ->andWhere($qb->expr()->in('m.id', ':arrayId'))
                ->setParameter('arrayId', $selected)
                ->getQuery()
                ->getResult();
        }

        $contItensChange = 0;
        foreach ($selectedItens as $subscription) {
            if($subscription) {
                $subscription->setStatus($status);
                $this->getEntityManager()->persist($subscription);

                $contItensChange++;
            }
        }

        $this->getEntityManager()->flush();
        $this->messages()->flashSuccess("Status alterado com suscesso!");
        return $this->redirect()->toRoute('admin/default', [
            'controller' => 'project',
            'action' => 'index',
        ], ['query'=>$filter]);

    }

}