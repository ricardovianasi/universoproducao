<?php
namespace Admin\Controller;

use Admin\Form\Tv\TvForm;
use Application\Entity\Tv\Tv;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Post\Post;

class TvController extends AbstractAdminController
	implements CrudInterface, PostInterface
{
	public function indexAction()
	{
		$tvItems = $this->search(Tv::class, ['site' => $this->getSiteIdFromUri()]);

		$this->getViewModel()->setVariables([
			'videos' => $tvItems,
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
		$result->setTemplate('admin/tv/create.phtml');

		return $result;
	}

	public function deleteAction($id)
	{
		$video = $this->getRepository(Tv::class)->find($id);
		$this->getEntityManager()->remove($video);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Video excluído com sucesso.');

		return $this->redirect()->toRoute('admin/tv', [self::SITE_PARAM => $this->getSiteIdFromUri()]);
	}

	public function persist($data, $id = null)
	{
		$form = new TvForm();

		if($id) {
			$video = $this->getRepository(Tv::class)->find($id);
		} else {
			$video = new Tv();
			$video->setAuthor($this->getAuthenticationService()->getIdentity());
			$video->setSite($this->getCurrentSite());
		}

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$video->setData($data);
				$this->getEntityManager()->persist($video);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Video atualizado com sucesso!");
				} else {
					$this->messages()->flashSuccess("Video criado com sucesso!");
					return $this->redirect()->toRoute('admin/tv', [
						'action' => 'update',
						'id' => $video->getId(),
						'site' => $this->getSiteIdFromUri()
					]);
				}
			}
		} else {
			$form->setData($video->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'site' => $this->getSiteIdFromUri(),
			'video' => $video
		]);
	}

}