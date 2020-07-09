<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use Application\Entity\File\File;
use Application\Entity\Institution\Institution;
use Application\Entity\Movie\Movie;
use Application\Entity\Project\Options;
use Application\Entity\Project\People;
use Application\Entity\Project\Project;
use Application\Entity\Registration\Registration;
use Application\Entity\State;
use Doctrine\Common\Collections\ArrayCollection;
use MeuUniverso\Form\ProjectForm;
use Zend\View\Model\ViewModel;

class ProjectRegistrationController extends AbstractMeuUniversoRegisterController
{
    const ERROR_REG_NOT_FOUND = 'x1001';
    const ERROR_REG_IS_CLOSED = 'x1002';
    const ERROR_REG_IS_NOT_EDIT = 'x1003';
    const ERROR_MOVIE_NOT_FOUNT = 'x1004';

    public function indexAction()
    {
        return [];
    }

    public function visualizarAction()
    {

    }

    public function novoAction()
    {
        return $this->persist('create');
    }

    public function editarAction()
    {
        /*$result = $this->persist('update');
        $result->setTemplate('meu-universo/movie-registration/editar.phtml');
        return $result;*/
    }

    protected function persist($method)
    {
        $idReg = $this->params()->fromRoute('id_reg');
        if(!$idReg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $reg = $this->getRepository(Registration::class)->findOneBy([
            'hash' => $idReg
        ]);

        if(!$reg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        if($method == 'create') {
            if(!$reg->isOpen()) {
                return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                    'code' => self::ERROR_REG_IS_CLOSED,
                    'id_reg' => $idReg
                ]]);
            }
        }

        $projectCategory = null;
        if($this->getRequest()->isPost()) {
            $options = $this->getRequest()->getPost('options');
            if(!empty($options['category'])) {
                $projectCategory = $options['category'];
            }
        }
        var_dump($projectCategory);

        $form = new ProjectForm($this->getEntityManager(), $projectCategory);
        $id = $this->params()->fromRoute('id');
        if($id) {
            $project = $this->getRepository(Project::class)->findOneBy([
                'id' => $id,
                'user' => $this->getAuthenticationService()->getIdentity()->getId()
            ]);

            if(!$project) {
                return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                    'code' => self::ERROR_REG_NOT_FOUND,
                    'id_reg' => $idReg
                ]]);
            }

        } else {
            $project = new Project();
            $project->setUser($this->getAuthenticationService()->getIdentity());
            $project->setRegistration($reg);
            $project->setEvent($reg->getEvent());
        }

        if($this->getRequest()->isPost()) {
            $data = array_replace_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $form->setData($data);

            if(!isset($data['no-validate'])) {
                if ($form->isValid()) {
                    //options
                    $options = new ArrayCollection();
                    if (!empty($data['options'])) {
                        foreach ($data['options'] as $opt) {
                            if (!empty($opt)) {
                                if (is_string($opt)) {
                                    $optEntity = $this->getRepository(Options::class)->find($opt);
                                    if ($optEntity) {
                                        $options->add($optEntity);
                                    }
                                } elseif (is_array($opt)) {
                                    foreach ($opt as $oId) {
                                        $optEntity = $this->getRepository(Options::class)->find($oId);
                                        if ($optEntity) {
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
                    if (!empty($data['state_production'])) {
                        $state = $this
                            ->getRepository(State::class)
                            ->find($data['state_production']);

                        $project->setStateProduction($state);
                    }
                    unset($data['state_production']);

                    //produtores
                    if (!empty($data['producers'])) {
                        foreach ($data['producers'] as $prod) {
                            $productor = $this->populatePeople($prod, People::TYPE_PRODUCER);
                            $productor->setProject($project);
                            $project->getPeoples()->add($productor);
                        }
                    }
                    unset($data['producers']);

                    //diretores
                    if (!empty($data['directors'])) {
                        foreach ($data['directors'] as $dir) {
                            $director = $this->populatePeople($dir, People::TYPE_DIRECTOR);
                            $director->setProject($project);
                            $project->getPeoples()->add($director);
                        }
                    }
                    unset($data['directors']);

                    //Tempo de duração
                    if (!empty($data['movie_length_hour'] && !empty($data['movie_length_minutes']))) {
                        $time = new \DateTime();
                        $time->setTime($data['movie_length_hour'], $data['movie_length_minutes']);
                        $project->setMovieLength($time);
                    }
                    unset($data['movie_length_hour']);
                    unset($data['movie_length_minutes']);

                    if (!empty($data['instituition'])) {
                        $instituition = new Institution();
                        $instituition->setData($data['instituition']);
                        $project->setInstituition($instituition);
                    }
                    unset($data['instituition']);


                    if (!empty($data['image'])) {
                        $image = $this->populateFiles($data['image'], true);
                        $project->setImage($image);
                    }

                    if(!empty($data['script'])) {
                        $script = $this->populateFiles($data['script']);
                        $project->setScript($script);
                    }

                    //Files
                    $files = new ArrayCollection();
                    if (!empty($data['files'])) {
                        foreach ($data['files'] as $f) {
                            $file = $this->populateFiles($f);
                            $files->add($file);
                        }
                    }
                    unset($data['image']);
                    unset($data['files']);
                    unset($data['script']);
                    $project->setFiles($files);

                    $project->setData($data);

                    $this->getEntityManager()->persist($project);
                    $this->getEntityManager()->flush();

                    //Enviar o e-mail de confirmação em caso de nova inscrição
                    if (!$id) {
                        //Enviar email de confirmação
                        $user = $this->getAuthenticationService()->getIdentity();
                        $msg = '<p>Olá <strong>' . $user->getName() . '</strong>!</p>';
                        $msg .= '<p>Informamos que o projeto <strong>' . $project->getTitle() . '</strong> foi inscrito com sucesso 
                       para participar da seleção de projetos do <strong>10º Brasil CineMundi</strong>, a ser realizado 
                       entre 27 de agosto e 01 de setembro de 2019, como parte integrante da programação da 13ª Mostra CineBH.</p>';
                        $msg .= '<p>O resultado da seleção está previsto para ser divulgado em <strong>julho de 2019</strong>, 
                    pelo site <a href="www.cinebh.com.br">www.cinebh.com.br</a>.</p>';
                        $msg .= '<p>Pedimos a gentileza de manter os dados do seu cadastro sempre atualizados para garantir 
                        a eficácia em nossa comunicação.</p>';

                        $msg .= '<p>Agradecemos sua participação!</p>';

                        $to[$user->getName()] = $user->getEmail();
                        $this->mailService()->simpleSendEmail($to, "Confirmação de inscrição de projeto", $msg);

                        return $this->redirect()->toRoute('meu-universo/default');
                    } else {
                        //$msg = '<p>O filme <strong>'.$project->getTitle().'</strong> foi atualizado com sucesso!';
                        //$this->meuUniversoMessages()->flashSuccess($msg);
                        return $this->redirect()->toRoute('meu-universo/default');
                    }
                }
            }
        } else {
            $form->setData($project->toArray());
        }

        $viewModel = new ViewModel();

        return $viewModel->setVariables([
            'form' => $form,
            'reg' => $reg,
            'project' => $project,
        ]);
    }

    public function deleteAction()
    {
        return [];
    }

    protected function populatePeople($data, $type)
    {
        $image = "";
        if(!empty($data['image']['name'])) {
            $file = $this->fileManipulation()->moveToRepository($data['image']);
            $image = $file['new_name'];
        }
        unset($data['image']);

        $people = new People();
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