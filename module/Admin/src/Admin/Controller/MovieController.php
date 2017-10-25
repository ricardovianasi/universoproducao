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
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;

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
            foreach ($obj->getEvents() as $e) {
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
		$result = $this->persist($data, $id);
		$result->setTemplate('admin/movie/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
        $op = $this->getRepository(Options::class)->find($id);
        $this->getEntityManager()->remove($op);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Opção excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'movie-options']);
	}

	public function persist($data, $id = null)
	{
		if($id) {
			$movie = $this->getRepository(Movie::class)->find($id);
			//$form->setRegistration($movie->getRegistration());
		} else {
			$movie = new Movie();
		}

		$registrations = [];
		foreach ($movie->getSubscriptions() as $s) {
		    $registrations[] = $s->getRegistration();
        }

        $form = new MovieForm($this->getEntityManager(), Options::STATUS_ENABLED, $registrations);

		if($this->getRequest()->isPost()) {
            $data = array_replace_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $form->setData($data);

			if($form->isValid()) {
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
		} else {
			$form->setData($movie->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'movie' => $movie
		]);
	}
}