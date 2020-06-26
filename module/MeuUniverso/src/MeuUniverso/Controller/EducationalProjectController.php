<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use Application\Entity\EducationalProject\Category;
use Application\Entity\EducationalProject\EducationalProject;
use Application\Entity\File\File;
use Application\Entity\Project\People;
use Application\Entity\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use MeuUniverso\Form\EducationalProjectForm;
use Zend\View\Model\ViewModel;

class EducationalProjectController extends AbstractMeuUniversoRegisterController
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

        if(!$reg->isOpen()) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_IS_CLOSED,
                'id_reg' => $idReg
            ]]);
        }

        $project = new EducationalProject();
        $project->setUser($this->getAuthenticationService()->getIdentity());
        $project->setRegistration($reg);
        $project->setEvent($reg->getEvent());

        $form = new EducationalProjectForm($this->getEntityManager(), $reg);
        if($this->getRequest()->isPost()) {
            $data = array_replace_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $form->setData($data);
            if($form->isValid()) {

                if(!empty($data['category'])) {
                    $cat = $this->getRepository(Category::class)->find($data['category']);
                    $project->setCategory($cat);
                }
                unset($data['category']);

                $files = new ArrayCollection();
                if(!empty($data['files'])) {
                    foreach ($data['files'] as $f) {
                        $file = $this->populateFiles($f);
                        $files->add($file);
                    }
                }
                unset($data['image']);
                unset($data['files']);
                $project->setFiles($files);

                $project->setData($data);
                $this->getEntityManager()->persist($project);
                $this->getEntityManager()->flush();

                //Enviar o e-mail de confirmação em caso de nova inscrição
                $user = $this->getAuthenticationService()->getIdentity();

                $msg = '<p>Olá <strong>'.$user->getName().'</strong>!</p>';
                $msg.= '<p>Informamos que o projeto <strong>'.$project->getTitle().'</strong> foi inscrito com sucesso 
                para participar da seleção da  <strong>'.$reg->getEvent()->getFullName().'</strong></p>';
                $msg.= '<p>O resultado da seleção está previsto para ser divulgado em <strong>agosto de 2020</strong>, 
                pelo site <a href="www.cineop.com.br">www.cineop.com.br</a>.</p>';
                $this->meuUniversoMessages()->flashSuccess($msg);

                $to[$user->getName()] = $user->getEmail();
                $this->mailService()->simpleSendEmail($to, "Confirmação de inscrição de projeto", $msg);

                return $this->redirect()->toRoute('meu-universo/default');

            }
        } else {
            $form->setData($project->toArray());
        }

        $viewModel = new ViewModel();

        return $viewModel->setVariables([
            'form' => $form,
            'reg' => $reg,
            'project' => $project
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
        isset($data['image']);

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