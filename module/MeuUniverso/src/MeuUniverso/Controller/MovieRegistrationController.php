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
use Application\Entity\Movie\MovieSubscription;
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
        /*$idReg = $this->params()->fromRoute('id_reg');
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
        }*/

        $id = $this->params()->fromRoute('id');
        $movie = $this->getRepository(Movie::class)->findOneBy([
           'id' => $id,
           'author' => $this->getAuthenticationService()->getIdentity()->getId()
        ]);

        if(!$movie) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND
            ]]);
        }

        return [
            'movie' => $movie
        ];
    }

    public function inscricaoAction()
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


        $finishedFrom = $reg->getOption(Options::MOVIE_ALLOW_FINISHED_FROM);
        $finishedTo = $reg->getOption(Options::MOVIE_ALLOW_FINISHED_TO);
        if(!($finishedFrom && $finishedTo)) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $dateFinishFrom = \DateTime::createFromFormat('d/m/Y', $finishedFrom->getValue());
        $dateFinishTo = \DateTime::createFromFormat('d/m/Y', $finishedTo->getValue());

        //verifica se existem filmes que podem ser inscritos no novo regulamento
        $movies = $this
            ->getRepository(Movie::class)
            ->createQueryBuilder('m')
            ->andWhere('m.endDateYear >= :finishedFrom')
            ->andWhere('m.endDateYear <= :finishedTo')
            ->andWhere('m.author = :idAuthor')
            ->setParameters([
                'finishedFrom' => $dateFinishFrom->format('Y'),
                'finishedTo' => $dateFinishTo->format('Y'),
                'idAuthor' => $this->getAuthenticationService()->getIdentity()->getId()
            ])
            ->getQuery()
            ->getResult();

        if(count($movies)) {
            $viewModel = new ViewModel();
            return $viewModel->setVariables([
                'reg' => $reg,
                'movies' => $movies
            ]);

        } else {
            return $this->redirect()->toRoute('meu-universo/movie', [
                'action' => 'novo',
                'id_reg' => $idReg
            ]);
        }
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

    public function editarInscricaoAction()
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

        //Evento
        //Verifica se o evento faz parte do regulamento em questão
        $idEvent = $this->params()->fromQuery('event');
        if(!$reg->getEventById($idEvent)) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        //Movie
        $idMovie = $this->params()->fromQuery('movie');
        $movie = $this->getRepository(Movie::class)->findOneBy([
            'id' => $idMovie,
            'author' => $this->getAuthenticationService()->getIdentity()->getId()
        ]);
        if(!$movie) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $finishedFrom = $reg->getOption(Options::MOVIE_ALLOW_FINISHED_FROM);
        $finishedTo = $reg->getOption(Options::MOVIE_ALLOW_FINISHED_TO);
        if(!($finishedFrom && $finishedTo)) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $dateFinishFrom = \DateTime::createFromFormat('d/m/Y', $finishedFrom->getValue());
        $dateFinishTo = \DateTime::createFromFormat('d/m/Y', $finishedTo->getValue());

        if(!($movie->getEndDateYear() >= $dateFinishFrom->format('Y') && $movie->getEndDateYear() <= $dateFinishTo->format('Y'))) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        if($this->getRequest()->isPost()) {

            $movieEvent = new MovieSubscription();
            $movieEvent->setMovie($movie);
            $movieEvent->setEvent($reg->getEventById($idEvent));
            $movieEvent->setRegistration($reg);

            $this->getEntityManager()->persist($movieEvent);
            $this->getEntityManager()->flush();

            //Enviar email de confirmação
            $user = $this->getAuthenticationService()->getIdentity();
            $msg = '<p>Olá <strong>'.$user->getName().'</strong>!</p>';
            $msg.= '<p>Informamos que o filme <strong>'.$movie->getTitle().'</strong>
                    foi inscrito com sucesso para participar da seleção da 
                    <strong>'.$reg->getEventById($idEvent)->getFullName().'</strong></p>';

            $msg.= '<p>O resultado para a seleção da 13ª CineOP está previsto para 14 de maio, e o da 12ª CineBH para 01 de agosto. Os resultados serão enviados pelo email cadastrado.</p>';
            $msg.= '<p>Pedimos a gentileza de manter os dados do seu cadastro sempre atualizados para garantir a eficácia em nossa comunicação!</p>';

            $to[$user->getName()] = $user->getEmail();
            $this->mailService()->simpleSendEmail($to, "Confirmação de inscrição de filme", $msg);

            $this->meuUniversoMessages()->flashSuccess($msg);
            return $this->redirect()->toRoute('meu-universo/default');
        }

        $viewModel = new ViewModel();
        return $viewModel->setVariables([
            'movie' => $movie,
            'event' => $reg->getEventById($idEvent),
            'reg' => $reg
        ]);
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
            //$movie->setRegistration($reg);
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
                    'inclusive' => false,
                    'date-time-format' => 'i',
                    'messages' => [
                        Duration::ERROR_IS_NOT_MARCH => 'Para Mostra Tiradentes não é permitido inscrição de filmes média'
                    ]
                ]));
            }

            if($form->isValid()) {

                if($id) {
                    $movieEvents = $this->getRepository(MovieSubscription::class)->findBy([
                        'movie' => $movie->getId()
                    ]);
                    foreach ($movieEvents as $mv) {
                        $this->getEntityManager()->remove($mv);
                    }
                }
                $movieEvents = new ArrayCollection();
                if(!empty($data['events'])) {
                    foreach ($data['events'] as $e) {
                        $movieEvent = new MovieSubscription();
                        $movieEvent->setMovie($movie);
                        $movieEvent->setEvent($this->getRepository(Event::class)->find($e));
                        $movieEvent->setRegistration($reg);

                        $movieEvents->add($movieEvent);
                    }
                    $movie->setSubscriptions($movieEvents);
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

                //Upload das fotos
                foreach ($movie->getMedias() as $m) {
                    $this->getEntityManager()->remove($m);
                }
                $newMedias = new ArrayCollection();
                if(!empty($data['medias'])) {
                    foreach ($data['medias'] as $me) {
                        $media = new Media();
                        $media->setMovie($movie);
                        $media->setCredits($me['caption']);
                        if(!empty($me['src'])) {
                            $media->setSrc($me['src']);
                        } elseif(!empty($me["file"])) {
                            $mediaFile = $me["file"];
                            if(!empty($mediaFile['name'])) {
                                $file = $this->fileManipulation()->moveToRepository($mediaFile);
                                $media->setSrc($file['new_name']);
                            }
                        }

                        $newMedias->add($media);
                    }
                }
                $movie->setMedias($newMedias);
                unset($data['medias']);

                $duration = $data['duration']; //im minutes -change to DateTime object
                $durationObj = \DateTime::createFromFormat('i', $duration);
                $data['duration'] = $durationObj;

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
                    foreach ($movie->getSubscriptions() as $e) {
                        $mostras.= '<li><strong>'.$e->getEvent()->getFullName().'</strong></li>';
                    }
                    $msg.= '<p><ul>'.$mostras.'</ul></p>';

                    $msg.= '<p>O resultado para a seleção da 13ª CineOP está previsto para 14 de maio, e o da 12ª CineBH para 01 de agosto. Os resultados serão enviados pelo email cadastrado.</p>';
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