<?php
namespace Admin\Controller;

use Admin\Form\Channel\CategoryForm;
use Application\Entity\Channel\Category;
use Application\Entity\Eufacoamostra;

class ChannelCategoryController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	public function indexAction()
	{
        $items = $this->search(Category::class);
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
		$result->setTemplate('admin/channel-category/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		$item = $this->getRepository(Category::class)->find($id);

        $this->getEntityManager()->remove($item);
        $this->messages()->flashSuccess('Item excluído com sucesso.');

		$this->getEntityManager()->flush();

		return $this->redirect()->toRoute('admin/default', ['controller'=>'channel-category']);
	}

	public function persist($data, $id = null)
	{
		$form = new CategoryForm();

		if($id) {
			$item = $this->getRepository(Category::class)->find($id);
		} else {
			$item = new Category();
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$item->setData($this->prepareDataPost(Category::class, $data, $item));
				$this->getEntityManager()->persist($item);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Item atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Item criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
					    'controller' => 'channel-category',
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