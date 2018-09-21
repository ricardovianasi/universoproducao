<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 08:08
 */
namespace Admin\Controller;

use Admin\Form\Movie\MovieForm;
use Admin\Form\Movie\MovieFormSearch;
use Admin\Form\Movie\MovieProgramingForm;
use Admin\Form\Movie\MovieStatusModalForm;
use Admin\Form\Movie\MovieSubscriptionForm;
use Admin\Form\Programing\ProgramingForm;
use Application\Entity\Event\Event;
use Application\Entity\Movie\Media;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\MovieSubscription;
use Application\Entity\Movie\Options;
use Application\Entity\Movie\ProducingInstitution;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Seminar\Thematic;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Model\JsonModel;

class MovieController extends AbstractAdminController
    implements CrudInterface, PostInterface
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $movieStatusModalForm = new MovieStatusModalForm($this->getEntityManager());
        $searchForm = new MovieFormSearch($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        if(empty($dataAttr)) {
            $dataAttr['events'] = $this->getDefaultEvent()?$this->getDefaultEvent()->getId():null;
        }
        $searchForm->setData($dataAttr);

        $searchForm->isValid();

        $data = $searchForm->getData();

        $items = $this->search(Movie::class, $data, ['createdAt' => 'DESC'], false, 25);
        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm,
            'searchData' => $dataAttr,
            'isFiltered' => !empty($data) ? true : false,
            'movieStatusModalForm' => $movieStatusModalForm
        ]);

        return $this->getViewModel();
    }

    public function exportAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $id = $this->params()->fromRoute('id');
        $item = $this->getRepository(Movie::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);

        return $this->prepareReport($preparedItems, 'movie' ,'pdf');
    }

    public function exportListAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(Movie::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'movie_list' ,'xlsx');
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

            $itemArray['end_date_month'] = $obj->getEndDateMonthName();

            //Author
            $author = [
                'author_id' => $obj->getAuthor() ? $obj->getAuthor()->getId() : "",
                'author_name' => $obj->getAuthor() ? $obj->getAuthor()->getName() : "",
                'author_email' => $obj->getAuthor() ? $obj->getAuthor()->getEmail() : "",
                'author_address' => $obj->getAuthor() ? $obj->getAuthor()->getFullAddress() : ""
            ];
            $phones = [];
            if($obj->getAuthor()) {
                foreach ($obj->getAuthor()->getPhones() as $phone) {
                    $phones[] = implode('|', $phone->_toArray());
                }
                $author['author_phones'] = implode(';', $phones);

                $itemArray = $itemArray+$author;
            }
            unset($itemArray['author']);

            //Events
            $events = [];
            foreach ($obj->getSubscriptions() as $e) {
                $events[] = $e->getEvent()->getShortName().":".Status::get($e->getStatus());
            }
            $itemArray['events'] = implode(';', $events);

            //Options
            unset($itemArray['options']);
            $opt_classification = "";
            if($opt_classification = $obj->getOption('classification')) {
                $opt_classification = $opt_classification->getName();
            }
            $itemArray['opt_classification'] = $opt_classification?$opt_classification:"";

            $opt_format_completed = "";
            if($opt_format_completed = $obj->getOption('format_completed')) {
                $opt_format_completed = $opt_format_completed->getName();
            }
            $itemArray['opt_format_completed'] = $opt_format_completed?$opt_format_completed:"";

            $opt_window = null;
            if($opt_window = $obj->getOption('window')) {
                $opt_window = $opt_window->getName();
            }
            $itemArray['opt_window'] = $opt_window?$opt_window:"";

            $opt_sound = null;
            if($opt_sound = $obj->getOption('sound')) {
                $opt_sound = $opt_sound->getName();
            }
            $itemArray['opt_sound'] = $opt_sound?$opt_sound:"";


            $opt_color = null;
            if($opt_color = $obj->getOption('color')) {
                $opt_color = $opt_color->getName();
            }
            $itemArray['opt_color'] = $opt_color?$opt_color:"";

            $opt_genre = null;
            if($opt_genre = $obj->getOption('genre')) {
                $opt_genre = $opt_genre->getName();
            }
            $itemArray['opt_genre'] = $opt_genre?$opt_genre:"";

            $opt_cat = null;
            if($opt_cat = $obj->getOption('general_category')) {
                $opt_cat = $opt_cat->getName();
            }
            $itemArray['opt_general_category'] = $opt_cat?$opt_cat:"";

            $opt_accessibility = null;
            if($opt_accessibility = $obj->getOption('accessibility')) {
                $opt_accessibility_concat = [];
                foreach ($opt_accessibility as $acc) {
                    $opt_accessibility_concat[] = $acc->getName();
                }
                $opt_accessibility = implode(';', $opt_accessibility_concat);
            }
            $itemArray['opt_accessibility'] = $opt_accessibility?$opt_accessibility:"";

            $opt_feature_directed = null;
            if($opt_feature_directed = $obj->getOption('feature_directed')) {
                $opt_feature_directed = $opt_feature_directed->getName();
            }
            $itemArray['opt_feature_directed'] = $opt_feature_directed?$opt_feature_directed:"";

            $opt_short_movie_category = null;
            if($opt_short_movie_category = $obj->getOption('short_movie_category')) {
                $opt_short_movie_category = $opt_short_movie_category->getName();
            }
            $itemArray['opt_short_movie_category'] = $opt_short_movie_category ? $opt_short_movie_category : "";

            //Duration
            $duration = "";
            if($obj->getDuration() instanceof \DateTime) {
                $duration = $obj->getDuration()->format('H:i:s');
            }
            $itemArray['duration'] = $duration;

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

    public function createAction($data)
    {
        return $this->persist($data);
    }

    public function updateAction($id, $data)
    {
        $return = $this->persist($data, $id);
        /** @var Movie $movie */
        $movie = $return->getVariable('movie');

        $movieSubscriptionForm = new MovieSubscriptionForm($movie);
        $movieSubscriptionForm->setData(['subscriptions'=>$movie->getSubscriptions()]);
        $return->subscriptionForm = $movieSubscriptionForm;

        return $return;
    }

    public function deleteAction($id)
    {
        $op = $this->getRepository(Movie::class)->find($id);
        $this->getEntityManager()->remove($op);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Opção excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'movie-options']);
    }

    public function persist($data, $id = null)
    {
        $form = new MovieForm($this->getEntityManager(), Options::STATUS_ENABLED);
        $form->getInputFilter()->remove('medias');

        if($id) {
            $movie = $this->getRepository(Movie::class)->find($id);
        } else {
            $movie = new Movie();
        }

        $programingForm = new MovieProgramingForm($this->getEntityManager(), null, $movie);

        //Popula os eventos baseado nos regulamentos que selecionados
        $registrationEvents = [];
        if($this->getRequest()->isPost()) {
            $registrationParam = $this->params()->fromPost('registration', []);
            $eventsParam = $this->params()->fromPost('events', []);
            foreach ($registrationParam as $subP) {
                $reg = $this->getRepository(Registration::class)->find($subP);
                if(key_exists($reg->getId(), $registrationEvents))
                    continue;

                $events = [];
                foreach ($reg->getEvents() as $e) {
                    $events[] = [
                        'id' => $e->getId(),
                        'name' => $e->getShortName(),
                        'selected' => isset($eventsParam[$reg->getId()][$e->getId()]) ? true : false
                    ];
                }
                $registrationEvents[$reg->getId()] = [
                    'name' => $reg->getName(),
                    'events' => $events
                ];

            }
        } elseif(count($movie->getSubscriptions())) {
            foreach ($movie->getSubscriptions() as $sub) {

                if(key_exists($sub->getRegistration()->getId(), $registrationEvents))
                    continue;

                $events = [];
                foreach ($sub->getRegistration()->getEvents() as $e) {
                    $events[] = [
                        'id' => $e->getId(),
                        'name' => $e->getShortName(),
                        'selected' => $movie->getSubscriptionByRegistrationEvent($sub->getRegistration()->getId(), $e->getId()) ? true : false
                    ];
                }
                $registrationEvents[$sub->getRegistration()->getId()] = [
                    'name' => $sub->getRegistration()->getName(),
                    'events' => $events
                ];
            }
        }

        $noValidate = $this->params()->fromPost('no-validate', false);

        if($this->getRequest()->isPost()) {
            $data = array_replace_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $form->setData($data);

            if(!$noValidate) {
                if($form->isValid()) {
                    //Inscrições de filmes
                    $subscriptionsToRemove = [];
                    foreach ($movie->getSubscriptions() as $sub) {
                        $subscriptionsToRemove[$sub->getId()] = $sub;
                    }

                    $subscriptions = new ArrayCollection();
                    if(!empty($data['events'])) {
                        foreach ($data['events'] as $regId => $events) {
                            $registration = $this
                                ->getRepository(Registration::class)
                                ->find($regId);

                            foreach ($events as $eventId=>$on) {
                                $event = $this
                                    ->getRepository(Event::class)
                                    ->find($eventId);

                                //if exist
                                $sub = $movie->getSubscriptionByRegistrationEvent($regId, $eventId);
                                if($sub) {
                                    unset($subscriptionsToRemove[$sub->getId()]);
                                } else {
                                    $sub = new MovieSubscription();
                                    $sub->setRegistration($registration);
                                    $sub->setEvent($event);
                                    $sub->setMovie($movie);
                                }

                                $subscriptions->add($sub);
                            }
                        }
                    }
                    $movie->setSubscriptions($subscriptions);
                    foreach ($subscriptionsToRemove as $subRemove) {
                        $this->getEntityManager()->remove($subRemove);
                    }
                    unset($data['events']);

                    //Author
                    $author = null;
                    if(!empty($data['author'])) {
                        $author = $this
                            ->getRepository(User::class)
                            ->find($data['author']);
                    }
                    $movie->setAuthor($author);
                    unset($data['author']);

                    //Options
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
                    $movie->setOptions($options);
                    unset($data['options']);

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

                    if(!empty($data['producing_institution'])) {
                        $dataInst = $data['producing_institution'];
                        if(!empty($dataInst['id'])) {
                            $instituition = $this->getRepository(ProducingInstitution::class)->find($dataInst['id']);
                        } else {
                            $instituition = new ProducingInstitution();
                            $instituition->setData($dataInst);
                        }
                        $movie->setProducingInstitution($instituition);
                    } else {
                        $movie->setProducingInstitution(null);
                    }
                    unset($data['producing_institution']);

                    /*$duration = $data['duration']; //im minutes -change to DateTime object
                    $durationObj = \DateTime::createFromFormat('i', $duration);
                    $data['duration'] = $durationObj;*/


                    $movie->setData($data);
                    $this->getEntityManager()->persist($movie);
                    $this->getEntityManager()->flush();

                    if($id) {
                        $this->messages()->success("Filme atualizado com sucesso!");
                        $form->setData($movie->toArray());
                    } else {
                        $this->messages()->flashSuccess("Filme criado com sucesso!");
                        return $this->redirect()->toRoute('admin/default', [
                            'controller' => 'movie',
                            'action' => 'update',
                            'id' => $movie->getId()
                        ]);
                    }
                }
            }
        } else {
            $form->setData($movie->toArray());
        }

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'movie' => $movie,
            'registrationEvents' => $registrationEvents,
            'programingForm' => $programingForm
        ]);
    }

    public function movieSubscriptionsAction()
    {
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();

            $id = $this->params()->fromRoute('id');
            $movie = $this->getRepository(Movie::class)->find($id);
            if(!$movie) {
                $this->messages()->flashError("Filme não encontrado!");
                return $this->redirect()->toRoute('admin/default', [
                    'controller' => 'movie',
                    'action' => 'index'
                ]);
            }

            foreach ($data['subscriptions'] as $subArray) {
                /** @var MovieSubscription $sub */
                $sub = $this->getRepository(MovieSubscription::class)->find($subArray['id']);
                if(!$sub) {
                    $this->messages()->flashError("Inscrições não encontrado!");
                    return $this->redirect()->toRoute('admin/default', [
                        'controller' => 'movie',
                        'action' => 'update',
                        'id' => $movie->getId()
                    ]);
                }

                $sub->setData($subArray);
                $this->getEntityManager()->persist($sub);

            }

            $this->getEntityManager()->flush();
            $this->messages()->flashSuccess("Filme atualizado com sucesso!");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'movie',
                'action' => 'update',
                'id' => $movie->getId()
            ]);


        } else {
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'movie',
                'action' => 'index'
            ]);
        }

    }

    public function statusAction()
    {
        if(!$this->getRequest()->isPost()) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'movie',
                'action' => 'index'
            ]);
        }

        $data = $this->getRequest()->getPost()->toArray();
        if(empty($data['event'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'movie',
                'action' => 'index'
            ]);
        }

        if(empty($data['status'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'movie',
                'action' => 'index'
            ]);
        }

        if(empty($data['filter'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'movie',
                'action' => 'index'
            ]);
        }

        $event = $data['event'];
        $status = $data['status'];
        parse_str(urldecode($data['filter']), $filter);

        if(empty($filter['selected'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'movie',
                'action' => 'index'
            ]);
        }

        $selectedItens = [];
        if($filter['selected'] == 'all') {
            $selectedItens = $this->search(Movie::class, $filter, [], true);
        } else {
            $selected = explode(',', $filter['selected']);
            if(!$selected) {
                $this->messages()->flashError("Erro ao processar solicitação.");
                return $this->redirect()->toRoute('admin/default', [
                    'controller' => 'movie',
                    'action' => 'index'
                ]);
            }

            $qb = $this
                ->getRepository(Movie::class)
                ->createQueryBuilder('m');

            $selectedItens = $qb
                ->andWhere($qb->expr()->in('m.id', ':arrayId'))
                ->setParameter('arrayId', $selected)
                ->getQuery()
                ->getResult();
        }

        $contItensChange = 0;
        foreach ($selectedItens as $item) {
            /** @var MovieSubscription $subscription */
            $subscription = $this
                ->getRepository(MovieSubscription::class)
                ->findOneBy([
                    'movie' => $item->getId(),
                    'event' => $event
                ]);

            if($subscription) {
                $subscription->setStatus($status);
                $this->getEntityManager()->persist($subscription);

                $contItensChange++;
            }
        }

        $this->getEntityManager()->flush();
        $this->messages()->flashSuccess("Status alterado com suscesso!");
        return $this->redirect()->toRoute('admin/default', [
            'controller' => 'movie',
            'action' => 'index'
        ]);

    }

    public function comunicadosAction()
    {
        $this->getViewModel()->setTerminal(true);

        $items = $this
            ->getRepository(Movie::class)
            ->createQueryBuilder('m')
            ->innerJoin('m.subscriptions', 's')
            ->andWhere('s.status = :status')
            ->andWhere('m.comunicadoEnviado = :comunicadoEnviado')
            ->andWhere('s.event = :idEvent')
            ->setParameters([
                'status' => 'not_selected',
                'comunicadoEnviado' => 0,
                'idEvent' => 1085
            ])
            ->getQuery()
            ->getResult();

        var_dump(count($items)); exit();
        $count = 0;
        foreach ($items as $item) {
            /** @var Movie $item */
            $item = new Movie();

            $msg = "<p>Prezado (a) ".$item->getAuthor()->getName().",</p>";
            $msg.= "<p>Comunicamos que o filme <strong>".$item->getTitle()."</strong> não foi selecionado para a 13ª CineOP - Mostra de Cinema de Ouro Preto.</p>";
            $msg.= "<p>Esclarecemos que os critérios que baseiam a seleção de filmes para festivais são múltiplos e podem variar de acordo com o perfil do evento, a safra anual e a composição formal das grades. Tentamos abarcar o maior número de filmes inscritos, tentando diversificar ao máximo propostas estéticas e temáticas, sempre pensando no público de cada mostra e numa seleção que dê conta do estado atual da produção audiovisual nacional. </p>";
            $msg.= "<p>Se algum filme não pertence à lista final de selecionados, é porque não se enquadrou nesses critérios e/ou por falta de espaço de exibição e limitação do período de duração do evento. </p>";
            $msg.= "<p><strong>Se você optou por participar também da seleção de filmes para a 12ª CineBH – Mostra de Cinema de Belo Horizonte, informamos que o filme passará por novo processo de seleção próximo à data de realização do evento. </strong></p>";
            $msg.= "<p>Agradecemos seu interesse e esperamos contar com sua participação nas próximas edições.</p>";
            $msg.= "<p>A programação da CineOP é gratuita e, estará disponível no site <a href='http://www.cineop.com.br'>www.cineop.com.br</a> a partir do dia 23 de maio. Aproveitamos para convidá-lo à participar das outras atividades da 13ª CineOP – debates, shows, cortejos e rodas de conversa.</p>";
            $msg.= "<p>Atenciosamente,<br />Coordenação – CineOP</p>";

            //$to[$item->getAuthor()->getName()] = 'ricardovianasi@gmail.com';
            /** @var \SendGrid\Response $return */
            $return = $this->mailService()->simpleSendEmail(
            [$item->getAuthor()->getName()=>$item->getAuthor()->getEmail()],
                //[$item->getAuthor()->getName()=>'ricardovianasi@gmail.com'],
                'Filmes - 13ª CineOP - Mostra de Cinema de Ouro Preto', $msg);

            $count++;
            echo "$count - Nome: " . $item->getAuthor()->getName();
            echo "<br />Email: " . $item->getAuthor()->getEmail();
            echo "<br />Filme: " . $item->getTitle();
            if($return->statusCode() == 202) {
                echo "<br /><b>******************-SUCESSO-******************</b><br /><br />";
                $item->setComunicadoEnviado(1);
                $this->getEntityManager()->persist($item);
                $this->getEntityManager()->flush();

            } else {
                echo "<b>******************-ERRO-******************</b><br /><br />";
                $item->setComunicadoEnviado(0);
            }
        }

        return $this->getViewModel();
    }
}