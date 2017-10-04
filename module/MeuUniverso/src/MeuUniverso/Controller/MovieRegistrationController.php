<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use Admin\Validator\Movie\Duration;
use Admin\Validator\Movie\Unique;
use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Movie\Media;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\MovieEvent;
use Application\Entity\Movie\Options as MovieOptions;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Validator\UniqueObject;
use MeuUniverso\Form\MovieForm;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class MovieRegistrationController extends AbstractMeuUniversoRegisterController
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

        $id = $this->params()->fromRoute('id');
        $movie = $this->getRepository(Movie::class)->findOneBy([
           'id' => $id,
           'author' => $this->getAuthenticationService()->getIdentity()->getId()
        ]);

        if(!$movie) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        return [
            'movie' => $movie,
            'reg' => $reg
        ];
    }

    public function novoAction()
    {
        return $this->persist('create');
    }

    public function editarAction()
    {
        $result = $this->persist('update');
        $result->setTemplate('meu-universo/movie-registration/editar.phtml');
        return $result;
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
        } elseif($method == 'update') {
            $now = new \DateTime();

            $allowEditRegister = false;
            $editUntil = $reg->getOption(Options::MOVIE_ALLOW_EDIT_REGISTRATION_TO);
            if($editUntil) {
                $editUntilDate = \DateTime::createFromFormat('d/m/Y', $editUntil);
                if($now < $editUntilDate) {
                    $allowEditRegister = true;
                }
            }

            if(!$allowEditRegister) {
                return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                    'code' => self::ERROR_REG_IS_NOT_EDIT,
                    'id_reg' => $idReg
                ]]);
            }
        }

        $form = new MovieForm($this->getEntityManager(), $reg);

        $id = $this->params()->fromRoute('id');
        if($id) {
            $movie = $this->getRepository(Movie::class)->findOneBy([
                'id' => $id,
                'author' => $this->getAuthenticationService()->getIdentity()->getId()
            ]);

            if(!$movie) {
                return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                    'code' => self::ERROR_REG_NOT_FOUND,
                    'id_reg' => $idReg
                ]]);
            }

        } else {
            $movie = new Movie();
            $movie->setRegistration($reg);
            $movie->setAuthor($this->getAuthenticationService()->getIdentity());
        }

        if($this->getRequest()->isPost()) {
            $data = array_replace_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $form->setData($data);


            //Validação do título do filme
            $movieTitleValidationOptions = [
                'object_manager' => $this->getRepository(Movie::class),
                'user_context' => $this->getAuthenticationService()->getIdentity()->getId(),
                'messages' => [
                    Unique::ERROR_OBJECT_NOT_UNIQUE => "O filme '%value%' já foi cadastrado"
                ]
            ];
            if($id) {
                $movieTitleValidationOptions['scape_id'] = $id;
            }

            $titleExist = new Unique($movieTitleValidationOptions);
            $titleInputFilter = $form->getInputFilter()->get('title');
            $titleInputFilter->getValidatorChain()->attach($titleExist);

            //Validação dos médias para mostra tiradentes
            $hasTiradentesSubscribe = false;
            if(!empty($data['events'])) {
                foreach ($data['events'] as $e) {
                    $event = $this->getRepository(Event::class)->find($e);
                    if($event && $event->getType() == EventType::MOSTRATIRADENTES) {
                        $hasTiradentesSubscribe = true;
                        break;
                    }
                }
            }
            if($hasTiradentesSubscribe) {
                $durationInputFilter = $form->getInputFilter()->get('duration');
                $durationInputFilter->getValidatorChain()->attach(new Duration([
                    'min' => (30*60),
                    'max' => (60*60),
                    'inclusive' => true,
                    'messages' => [
                        Duration::ERROR_IS_NOT_MARCH => 'Para Mostra Tiradentes não é permitido inscrição de filmes média'
                    ]
                ]));
            }

            if($form->isValid()) {

                if($id) {
                    $movieEvents = $this->getRepository(MovieEvent::class)->findBy([
                        'movie' => $movie->getId()
                    ]);
                    foreach ($movieEvents as $mv) {
                        $this->getEntityManager()->remove($mv);
                    }
                }
                $movieEvents = new ArrayCollection();
                if(!empty($data['events'])) {
                    foreach ($data['events'] as $e) {
                        $movieEvent = new MovieEvent();
                        $movieEvent->setMovie($movie);
                        $movieEvent->setEvent($this->getRepository(Event::class)->find($e));

                        $movieEvents->add($movieEvent);
                    }
                    $movie->setEvents($movieEvents);
                }
                unset($data['events']);

                $options = new ArrayCollection();
                if(!empty($data['options'])) {
                    foreach ($data['options'] as $opt) {
                        if(!empty($opt)) {
                            if(is_string($opt)) {
                                $optEntity = $this->getRepository(MovieOptions::class)->find($opt);
                                if($optEntity) {
                                    $options->add($optEntity);
                                }
                            } elseif(is_array($opt)) {
                                foreach ($opt as $oId) {
                                    $optEntity = $this->getRepository(MovieOptions::class)->find($oId);
                                    if($optEntity) {
                                        $options->add($optEntity);
                                    }
                                }
                            }
                        }
                    }
                }
                $movie->setOptions($options);
                unset($data['options']);

                //Upload das fotos

                $newMedias = new ArrayCollection();
                for($i=1; $i<3; $i++) {

                    if(!empty($data["media_id_$i"])) {
                        $mediaId = $data["media_id_$i"];
                        $media = $movie->getMediaById($mediaId);
                    } else {
                        $media = new Media();
                        $media->setMovie($movie);
                    }

                    if(!empty($data["media_file_$i"])) {
                        $mediaFile = $data["media_file_$i"];
                        $credits = !empty($data["media_caption_$i"]) ? $data["media_caption_$i"] : '';
                        if(!empty($mediaFile['name'])) {
                            //novo arquivo
                            if($media->getId()) {
                                $movie->getMedias()->removeElement($media);
                                $this->getEntityManager()->remove($media);
                            }

                            $file = $this->fileManipulation()->moveToRepository($mediaFile);

                            $media->setSrc($file['new_name']);
                            $media->setCredits($credits);

                            $movie->getMedias()->add($media);
                        } else {
                            if($media->getId()) {
                                $media->setCredits($credits);
                                $this->getEntityManager()->persist($media);
                            }
                        }
                    }

                    unset($data["media_file_$i"]);
                    unset($data["media_caption_$i"]);
                    unset($data["media_id_$i"]);
                }

                $movie->setData($data);

                $this->getEntityManager()->persist($movie);
                $this->getEntityManager()->flush();

                //Enviar o e-mail de confirmação em caso de nova inscrição
                if(!$id) {
                    //Enviar email de confirmação
                    $user = $this->getAuthenticationService()->getIdentity();
                    $msg = '<p>Olá <strong>'.$user->getName().'</strong>!</p>';
                    $msg.= '<p>Informamos que o filme <strong>'.$movie->getTitle().'</strong>
                    foi inscrito com sucesso para participar da seleção da:</p>';

                    $mostras = "";
                    foreach ($movie->getEvents() as $e) {
                        $mostras.= '<li><strong>'.$e->getEvent()->getFullName().'</strong></li>';
                    }
                    $msg.= '<p><ul>'.$mostras.'</ul></p>';

                    $msg.= '<p>O resultado da seleção está previsto para ser divulgado até o dia 23 de dezembro de 2017, pelo e-mail cadastrado.</p>';
                    $msg.= '<p>Pedimos a gentileza de manter os dados do seu cadastro sempre atualizados para garantir a eficácia em nossa comunicação!</p>';

                    $to[$user->getName()] = $user->getEmail();
                    $this->mailService()->simpleSendEmail($to, "Confirmação de inscrição de filme", $msg);

                    $this->meuUniversoMessages()->flashSuccess($msg);
                    return $this->redirect()->toRoute('meu-universo/default');
                } else {
                    $msg = '<p>O filme <strong>'.$movie->getTitle().'</strong> foi atualizado com sucesso!';
                    $this->meuUniversoMessages()->flashSuccess($msg);
                    return $this->redirect()->toRoute('meu-universo/default');
                }
            }
        } else {
            $form->setData($movie->toArray());
        }

        $viewModel = new ViewModel();

        return $viewModel->setVariables([
            'form' => $form,
            'reg' => $reg,
            'movie' => $movie
        ]);
    }

    public function deleteAction()
    {
        return [];
    }
}