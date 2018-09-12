<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 08:08
 */
namespace Admin\Controller;

use Admin\Form\Movie\MovieProgramingForm;
use Admin\Form\Movie\OptionsForm;
use Admin\Form\Registration\MovieRegistrationForm;
use Application\Entity\Event\Event;
use Application\Entity\Event\Place;
use Application\Entity\Event\SubEvent;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\Options;
use Application\Entity\Programing\Meta;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Doctrine\Common\Collections\ArrayCollection;

class MovieProgramingController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	/**
	 * @return ViewModel
	 */
	public function indexAction()
	{
        $searchForm = new MovieProgramingForm($this->getEntityManager(), $this->getDefaultEvent());
        $dataAttr = $this->params()->fromQuery();
        if(empty($dataAttr)) {
            $dataAttr['event'] = $this->getDefaultEvent()?$this->getDefaultEvent()->getId():null;
        }
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();
        $data['type'] = Type::MOVIE;
        unset($data['meta']);
        unset($data['movie']);

        $items = $this->search(Programing::class, $data, ['date'=>'ASC', 'startTime'=>'ASC'], true);

        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm
        ]);

        return $this->getViewModel();
	}

	public function createAction($data)
	{
		return $this->persist($data);
	}

	public function updateAction($id, $data)
	{
		$result = $this->persist($data, $id);
		$result->setTemplate('admin/movie-programing/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
        $p = $this->getRepository(Programing::class)->find($id);
        $this->getEntityManager()->remove($p);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Programação excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'movie-programing']);
	}

	public function persist($data, $id = null)
	{
        $event = null;
		if($id) {
			$programing = $this->getRepository(Programing::class)->find($id);
			$event = $programing->getEvent();
		} else {
			$programing = new Programing();
		}

		if($paramTtpe = $this->params()->fromPost('type')) {
		    $programing->setType($paramTtpe);
        }

        if($this->params()->fromPost('event')) {
		    $event = $this
                ->getRepository(Event::class)
                ->find($this->params()->fromPost('event'));
        }

        $form = new MovieProgramingForm($this->getEntityManager(), $event);

        $noValidate = $this->params()->fromPost('no-validate', false);
		if($this->getRequest()->isPost()) {
			$form->setData($data);
            if(!$noValidate) {
                if ($form->isValid()) {

                    unset($data['available_places']);

                    $event = null;
                    if (!empty($data['event'])) {
                        $event = $this->getRepository(Event::class)->find($data['event']);
                    }
                    $programing->setEvent($event);
                    unset($data['event']);

                    $subEvent = null;
                    if (!empty($data['sub_event'])) {
                        $subEvent = $this->getRepository(SubEvent::class)->find($data['sub_event']);
                    }
                    $programing->setSubEvent($subEvent);
                    unset($data['sub_event']);

                    $place = null;
                    if (!empty($data['place'])) {
                        $place = $this->getRepository(Place::class)->find($data['place']);
                    }
                    $programing->setPlace($place);
                    unset($data['place']);

                    $metaColl = new ArrayCollection();
                    foreach ($programing->getMeta() as $m) {
                        $this->getEntityManager()->remove($m);
                    }
                    if(!empty($data['meta'])) {
                        foreach ($data['meta'] as $key=>$mValue) {
                            if(!empty($mValue)) {
                                $meta = new Meta();
                                $meta->setPrograming($programing);
                                $meta->setName($key);
                                $meta->setValue($mValue);

                                $metaColl->add($meta);
                            }
                        }
                    }
                    $programing->setMeta($metaColl);
                    unset($data['meta']);

                    foreach ($programing->getChildren() as $c) {
                        $this->getEntityManager()->remove($c);
                    }

                    if($data['type'] == Type::MOVIE) {
                        $movie = null;
                        if (!empty($data['movie'])) {
                            $movie = $this->getRepository(Movie::class)->find($data['movie']);
                        }
                        $programing->setObjectId($movie->getId());
                        unset($data['movie']);
                    } elseif($data['type'] == Type::SESSION) {
                        $sessionsParam = json_decode($data['sessions'], true);
                        $sessions = new ArrayCollection();
                        $count = 0;
                        foreach ($sessionsParam as $s) {
                            $movie = $this
                                ->getRepository(Movie::class)
                                ->find($s['id']);

                            $itemToSession = new Programing();
                            $itemToSession->setType(Type::MOVIE);
                            $itemToSession->setEvent($event);
                            $itemToSession->setParent($programing);
                            $itemToSession->setObjectId($movie->getId());
                            $itemToSession->setOrder($count++);
                            $itemToSession->setSubEvent($subEvent);
                            $itemToSession->setPlace($place);

                            $sessions->add($itemToSession);
                        }
                        $programing->setChildren($sessions);
                    }

                    $programing->setData($data);

                    $this->getEntityManager()->persist($programing);
                    $this->getEntityManager()->flush();

                    if ($id) {
                        $this->messages()->success("Programação atualizada com sucesso!");
                    } else {
                        $this->messages()->flashSuccess("Programação incluída com sucesso!");
                        return $this->redirect()->toRoute('admin/default', [
                            'controller' => 'movie-programing',
                            'action' => 'update',
                            'id' => $programing->getId()
                        ]);
                    }
                }
            }
		} else {
			$form->setData($programing->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'programing' => $programing
		]);
	}
}