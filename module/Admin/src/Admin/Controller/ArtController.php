<?php
namespace Admin\Controller;

use Admin\Form\Art\ArtForm;
use Admin\Form\Art\ArtProgramingForm;
use Admin\Form\Programing\ProgramingForm;
use Admin\Form\Workshop\WorkshopForm;
use Application\Entity\Art\Art;
use Application\Entity\Art\Category;
use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Event\Place;
use Application\Entity\File\File;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Workshop\Workshop;
use Doctrine\Common\Collections\ArrayCollection;

class ArtController extends AbstractAdminController implements CrudInterface
{
	public function indexAction()
	{
        $searchForm = new ArtForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

		$items = $this->search(Art::class, $data);

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
		$result->setTemplate('admin/art/create.phtml');
		return $result;
	}

	public function deleteAction($id)
	{
		$art = $this->getRepository(Art::class)->find($id);
		$this->getEntityManager()->remove($art);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Arte excluída com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'art']);
	}

	public function persist($data, $id = null)
	{
		if($id) {
			$art = $this->getRepository(Art::class)->find($id);
		} else {
			$art = new Art();
		}

		$programingForm = new ArtProgramingForm($this->getEntityManager());
        $form = new ArtForm($this->getEntityManager());
		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {

			    $event = null;
			    if(!empty($data['event'])) {
			        $event = $this->getRepository(Event::class)->find($data['event']);
                }
                $art->setEvent($event);
			    unset($data['event']);

                $category = null;
                if(!empty($data['category'])) {
                    $category = $this->getRepository(Category::class)->find($data['category']);
                }
                $art->setCategory($category);
                unset($data['category']);

                $programing = [];
                if(!empty($data['programing'])) {
                    $programing = $data['programing'];
                }
                unset($data['programing']);
                foreach ($art->getPrograming() as $p) {
                    $this->getEntityManager()->remove($p);
                }

                $files = new ArrayCollection();
                if(!empty($data['files'])) {
                    foreach ($data['files'] as $dataFile) {
                        $file = new File();
                        $file->setData($dataFile);
                        $files->add($file);
                    }
                }
                $art->setFiles($files);
                unset($data['files']);

                $art->setData($data);
                $this->getEntityManager()->persist($art);
                $this->getEntityManager()->flush();

                //Persiste a grade da programação
                foreach ($programing as $pro) {
                    $artProg = new Programing();
                    $artProg->setEvent($event);
                    $artProg->setType(Type::ART);
                    $artProg->setObjectId($art->getId());

                    if(!empty($pro['place'])) {
                        $place = $this
                            ->getRepository(Place::class)
                            ->find($pro['place']);

                        $pro['place'] = $place;
                    }

                    $artProg->setData($pro);
                    $this->getEntityManager()->persist($artProg);
                }

                $this->getEntityManager()->flush();
                $this->getEntityManager()->refresh($art);

				if($id) {
					$this->messages()->success("Arte atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Arte criada com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'art',
						'action' => 'update',
						'id' => $art->getId()
					]);
				}
			}
		} else {
			$form->setData($art->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'programingForm' => $programingForm,
			'art' => $art
		]);
	}
}