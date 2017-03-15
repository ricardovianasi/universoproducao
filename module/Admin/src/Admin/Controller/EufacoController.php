<?php
namespace Admin\Controller;

use Admin\Form\EufacoForm;
use Application\Entity\Eufacoamostra;

class EufacoController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	public function indexAction()
	{
		$items = $this->search(Eufacoamostra::class, ['site' => $this->getSiteIdFromUri()]);
		$this->getViewModel()->setVariables([
			'items' => $items,
            'site' => $this->getSiteIdFromUri()
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
		$result->setTemplate('admin/eufaco/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		$item = $this->getRepository(Eufacoamostra::class)->find($id);

        $this->getEntityManager()->remove($item);
        $this->messages()->flashSuccess('Item excluído com sucesso.');

		$this->getEntityManager()->flush();

		return $this->redirect()->toRoute('admin/eufacoamostra', ['site'=>$this->getSiteIdFromUri()]);
	}

	public function persist($data, $id = null)
	{
		$form = new EufacoForm();

		if($id) {
			$item = $this->getRepository(Eufacoamostra::class)->find($id);
		} else {
			$item = new Eufacoamostra();
			$item->setSite($this->getCurrentSite());
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$item->setData($this->prepareDataPost(Eufacoamostra::class, $data, $item));
				$this->getEntityManager()->persist($item);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Item atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Item criado com sucesso!");
					return $this->redirect()->toRoute('admin/eufacoamostra', [
						'action' => 'update',
						'id' => $item->getId(),
                        'site' => $this->getSiteIdFromUri()
					]);
				}
			}
		} else {
			$form->setData($item->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'item' => $item,
            'site' => $this->getSiteIdFromUri()
		]);
	}

}