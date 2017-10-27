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
use Admin\Form\Movie\MovieSubscriptionForm;
use Application\Entity\Event\Event;
use Application\Entity\Movie\Media;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\MovieSubscription;
use Application\Entity\Movie\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Feed\PubSubHubbub\Model\Subscription;

class MovieController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	/**
	 * @return ViewModel
	 */
	public function indexAction()
	{
	    $searchForm = new MovieFormSearch($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        $searchForm->isValid();

        $data = $searchForm->getData();

        $items = $this->search(Movie::class, $data, ['createdAt' => 'DESC']);
        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm,
            'searchData' => $dataAttr
        ]);

        return $this->getViewModel();
	}

	public function exportListAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(Movie::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
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
                'author_id' => $obj->getAuthor()->getId(),
                'author_name' => $obj->getAuthor()->getName(),
                'author_email' => $obj->getAuthor()->getEmail(),
                'author_address' => $obj->getAuthor()->getFullAddress()
            ];
            $phones = [];
            foreach ($obj->getAuthor()->getPhones() as $phone) {
                $phones[] = implode('|', $phone->_toArray());
            }
            $author['author_phones'] = implode(';', $phones);

            $itemArray = $itemArray+$author;
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

            $preparedItems[] = ['movie'=>$itemArray];
        }

        $downloadToken = null;
        if(!empty($dataAttr['downloadToken'])) {
            $downloadToken = $dataAttr['downloadToken'];
        }

        return $this->prepareReport($preparedItems, 'movie_list' ,'xlsx', $downloadToken);
    }

	public function createAction($data)
	{
		return $this->persist($data);
	}

	public function updateAction($id, $data)
	{
		$return = $this->persist($data, $id);
        $movie = $return->getVariable('movie');

        $movieSubscriptionForm = new MovieSubscriptionForm($movie);
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

        if($id) {
            $movie = $this->getRepository(Movie::class)->find($id);
        } else {
            $movie = new Movie();
        }

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

                    if($id) {
                        $this->messages()->success("Filme atualizado com sucesso!");
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
            'registrationEvents' => $registrationEvents
		]);
	}
}