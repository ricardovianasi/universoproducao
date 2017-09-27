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
use Application\Entity\Movie\Options;
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
            if($now >= $reg->getEditRegisterUntil()) {
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
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $data = (array) $this->getRequest()->getPost();

                //Events
                if(!empty($data['events'])) {
                    if($id) {
                        $movieEvents = $this->getRepository(MovieEvent::class)->findBy([
                            'movie' => $movie->getId()
                        ]);
                        foreach ($movieEvents as $mv) {
                            //Se não existir na lista, limpa do banco
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

                if(!empty($data['classification'])) {
                    $movie->setClassification($this->getRepository(Options::class)->find($data['classification']));
                } else {
                    $movie->setClassification(null);
                }
                unset($data['classification']);

                if(!empty($data['format'])) {
                    $movie->setFormat($this->getRepository(Options::class)->find($data['format']));
                } else {
                    $movie->setFormat(null);
                }
                unset($data['format']);

                if(!empty($data['category'])) {
                    $movie->setCategory($this->getRepository(Options::class)->find($data['category']));
                } else {
                    $movie->setCategory(null);
                }
                unset($data['category']);

                if(!empty($data['window'])) {
                    $movie->setWindow($this->getRepository(Options::class)->find($data['window']));
                } else {
                    $movie->setWindow(null);
                }
                unset($data['window']);

                if(!empty($data['sound'])) {
                    $movie->setSound($this->getRepository(Options::class)->find($data['sound']));
                } else {
                    $movie->setSound(null);
                }
                unset($data['sound']);

                if(!empty($data['color'])) {
                    $movie->setColor($this->getRepository(Options::class)->find($data['color']));
                } else {
                    $movie->setColor(null);
                }
                unset($data['color']);

                if(!empty($data['genre'])) {
                    $movie->setGenre($this->getRepository(Options::class)->find($data['genre']));
                } else {
                    $movie->setGenre(null);
                }
                unset($data['genre']);

                if(!empty($data['accessibility'])) {
                    $movie->setAccessibility($this->getRepository(Options::class)->find($data['accessibility']));
                } else {
                    $movie->setAccessibility(null);
                }
                unset($data['accessibility']);

                //Upload das fotos
                $medias = new ArrayCollection();
                foreach ($movie->getMedias() as $m) {
                    $this->getEntityManager()->remove($m);
                }
                if(!empty($data['media'])) {
                    foreach ($data['media'] as $m) {

                    }
                }
                unset($data['media']);
                unset($data['media_caption']);

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

                    $msg.= '<p>O resultado da seleçãoo está previsto para ser divulgado até o dia 20 de julho de 2017, pelo site www.cineop.com.br.</p>';
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