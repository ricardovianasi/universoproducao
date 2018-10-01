<?php
namespace Admin\Controller;

use Admin\Form\Channel\VideoForm;
use Application\Entity\Channel\Video;
use Application\Entity\Channel\Category;
use Doctrine\Common\Collections\ArrayCollection;

class ChannelController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	public function indexAction()
	{
        $items = $this->search(Video::class, [], ['date' => 'DESC']);
        $this->getViewModel()->setVariables([
            'items' => $items
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
		$result->setTemplate('admin/channel/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		$item = $this->getRepository(Video::class)->find($id);

        $this->getEntityManager()->remove($item);
        $this->messages()->flashSuccess('Item excluído com sucesso.');

		$this->getEntityManager()->flush();

		return $this->redirect()->toRoute('admin/default', ['controller'=>'channel']);
	}

	public function persist($data, $id = null)
	{
		$form = new VideoForm($this->getEntityManager());

		if($id) {
			$item = $this->getRepository(Video::class)->find($id);
		} else {
			$item = new Video();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {

			    $categories = new ArrayCollection();
			    foreach ($data['categories'] as $catId) {
			        $cat = $this->getRepository(Category::class)->find($catId);
			        $categories->add($cat);
                }
                $item->setCategories($categories);
                unset($data['categories']);

                $item->setData($this->prepareDataPost(Video::class, $data, $item));

				$this->getEntityManager()->persist($item);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Item atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Item criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
                        'controller'=>'channel',
						'action' => 'update',
						'id' => $item->getId(),
					]);
				}
			}
		} else {
			$form->setData($item->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'item' => $item
		]);
	}

}