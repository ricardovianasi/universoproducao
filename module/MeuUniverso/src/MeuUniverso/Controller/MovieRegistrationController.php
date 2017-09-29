<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use Application\Entity\Event\Event;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\MovieEvent;
use Application\Entity\Movie\Options as MovieOptions;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use MeuUniverso\Form\MovieForm;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class MovieRegistrationController extends AbstractMeuUniversoRegisterController
{
    const ERROR_REG_NOT_FOUND = 'x1001';
    const ERROR_REG_IS_CLOSED = 'x1002';
    const ERROR_REG_IS_NOT_EDIT = 'x1003';

    public function indexAction()
    {
        return [];
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
            $movie = $this->getRepository(Movie::class)->find($id);
        } else {
            $movie = new Movie();
            $movie->setRegistration($reg);
            $movie->setAuthor($this->getAuthenticationService()->getIdentity());
        }

        if($this->getRequest()->isPost()) {
            $data = (array) $this->getRequest()->getPost();
            $form->setData($data);
            if($form->isValid()) {
                //Events
                if(!empty($data['events'])) {
                    if($id) {
                        $movieEvents = $this->getRepository(MovieEvent::class)->findBy([
                            'movie' => $movie->getId()
                        ]);
                        foreach ($movieEvents as $mv) {
                            $this->getEntityManager()->remove($mv);
                        }
                    } else {
                        $movieEvents = new ArrayCollection();
                        foreach ($data['events'] as $e) {
                            $movieEvent = new MovieEvent();
                            $movieEvent->setMovie($movie);
                            $movieEvent->setEvent($this->getRepository(Event::class)->find($e));

                            $movieEvents->add($movieEvent);
                        }
                        $movie->setEvents($movieEvents);
                    }
                }
                unset($data['events']);

                $options = new ArrayCollection();
                if(!empty($data['options_category'])) {
                    $opt = $this->getRepository(MovieOptions::class)->find($data['options_category']);
                    $options->add($opt);
                }
                unset($data['options_category']);

                if(!empty($data['options_window'])) {
                    $opt = $this->getRepository(MovieOptions::class)->find($data['options_window']);
                    $options->add($opt);
                }
                unset($data['options_window']);

                if(!empty($data['options_sound'])) {
                    $opt = $this->getRepository(MovieOptions::class)->find($data['options_sound']);
                    $options->add($opt);
                }
                unset($data['options_sound']);

               if(!empty($data['options_color'])) {
                    $opt = $this->getRepository(MovieOptions::class)->find($data['options_color']);
                    $options->add($opt);
               }
               unset($data['options_color']);


                if(!empty($data['options_genre'])) {
                    $opt = $this->getRepository(MovieOptions::class)->find($data['options_genre']);
                    $options->add($opt);
                }
                unset($data['options_accessibility']);

                if(!empty($data['options_accessibility'])) {
                    $opt = $this->getRepository(MovieOptions::class)->find($data['options_genre']);
                    $options->add($opt);
                }
                unset($data['options_accessibility']);

                if(!empty($data['options_feature_directed'])) {
                    $opt = $this->getRepository(MovieOptions::class)->find($data['options_feature_directed']);
                    $options->add($opt);
                }
                unset($data['options_feature_directed']);

                if(!empty($data['options_short_movie_category'])) {
                    $opt = $this->getRepository(MovieOptions::class)->find($data['options_short_movie_category']);
                    $options->add($opt);
                }
                unset($data['options_short_movie_category']);
                $movie->setOptions($options);

                //Upload das fotos
                $medias = new ArrayCollection();
                /*foreach ($movie->getMedias() as $m) {
                    $this->getEntityManager()->remove($m);
                }
                if(!empty($data['media'])) {
                    foreach ($data['media'] as $m) {

                    }
                }
                unset($data['media']);
                unset($data['media_caption']);*/

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