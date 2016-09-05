<?php
namespace Admin\Controller;

use Application\Entity\Programation\Highlight;
use Admin\Form\Programation\Highlight as HighlightForm;
use Application\Entity\Tv\Tv;

class ProgramationController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	public function indexAction()
	{
		$items = $this->search(Highlight::class, ['site' => $this->getSiteIdFromUri()]);

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
		$result->setTemplate('admin/programation/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		$item = $this->getRepository(Highlight::class)->find($id);
		$this->getEntityManager()->remove($item);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Destaque excluído com sucesso.');

		return $this->redirect()->toRoute('admin/programation', [self::SITE_PARAM => $this->getSiteIdFromUri()]);
	}

	public function persist($data, $id = null)
	{
		$form = new HighlightForm();

		if($id) {
			$prog = $this->getRepository(Highlight::class)->find($id);
		} else {
			$prog = new Highlight();
			$prog->setAuthor($this->getAuthenticationService()->getIdentity());
			$prog->setSite($this->getCurrentSite());
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$prog->setData($data);
				$this->getEntityManager()->persist($prog);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Destaque atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Destaque criado com sucesso!");
					return $this->redirect()->toRoute('admin/programation', [
						'action' => 'update',
						'id' => $prog->getId(),
						'site' => $this->getSiteIdFromUri()
					]);
				}
			}
		} else {
			$form->setData($prog->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'site' => $this->getSiteIdFromUri(),
			'prog' => $prog
		]);
	}

}