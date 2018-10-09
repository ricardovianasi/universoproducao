<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 20/02/2016
 * Time: 11:35
 */

namespace Admin\Controller;

use Admin\Form\Proposal\ArtisticProposalForm;
use Admin\Form\User\ResetPasswordForm;
use Admin\Form\User\UserForm;
use Admin\Form\User\UserSearch;
use Application\Entity\AdminUser\User;
use Application\Entity\Proposal\ArtisticProposal;
use Application\Entity\Proposal\ArtisticProposalCategory;
use Util\Security\Crypt;

class ArtisticProposalController extends AbstractAdminController
	implements CrudInterface

{
	public function indexAction()
	{
		$searchForm = new ArtisticProposalForm();
		$dataAttr = $this->params()->fromQuery();
		$searchForm->setData($dataAttr);
		$searchForm->isValid();

		$items = $this->search(ArtisticProposal::class, $searchForm->getData());

		$this->getViewModel()->setVariables([
			'searchForm' => $searchForm,
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
        $result->setTemplate('admin/artistic-proposal/create.phtml');
        return $result;
	}

	public function deleteAction($id)
	{
		$proposal = $this->getRepository(ArtisticProposal::class)->find($id);

		$this->getEntityManager()->remove($proposal);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Proposta excluída com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'artistic-proposal']);
	}

	public function persist($data, $id = null)
	{
		$form = new ArtisticProposalForm($this->getEntityManager());
		if($id) {
			$proposal = $this->getRepository(ArtisticProposal::class)->find($id);
		} else {
            $proposal = new ArtisticProposal();
        }

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$validData = $form->getData();

                $user = null;
                if($this->getRequest()->isPost()) {
                    if (!empty($data['user'])) {
                        $user = $this
                            ->getRepository(\Application\Entity\User\User::class)
                            ->find($data['user']);
                    }
                }
                $proposal->setAuthor($user);
                unset($validData['user']);


                if(!empty($validData['category'])) {
				    $cat = $this
                        ->getRepository(ArtisticProposalCategory::class)
                        ->find($validData['category']);
				    $proposal->setCategory($cat);
                }
                unset($validData['category']);

				$proposal->setData($validData);

				$this->getEntityManager()->persist($proposal);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Proposta atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Proposta criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'artistic-proposal',
						'action' => 'update',
						'id' => $proposal->getId()
					]);
				}
			}
		} else {
			$form->setData($proposal->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
			'proposal' => $proposal
		]);
	}

    public function exportAction()
    {
        //recupera os itens
        $id = $this->params()->fromRoute('id');
        $item = $this->getRepository(ArtisticProposal::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);

        return $this->prepareReport($preparedItems, 'artistic-proposal' ,'pdf');
    }

    protected function prepareItemsForReports($items)
    {
        if(!is_array($items)) {
            $items = [$items];
        }

        $preparedItems = [];
        foreach ($items as $obj) {
            //$obj = new ArtisticProposal();
            $array = $obj->toArray();
            unset($array['updated_at']);
            unset($array['default_input_filters']);

            $array['category'] = $obj->getCategory()?$obj->getCategory()->getName() : "";

            //Created At
            $createdAt = "";
            if($obj->getCreatedAt() instanceof \DateTime) {
                $createdAt = $obj->getCreatedAt()->format('d/m/Y H:i:s');
            }
            $array['created_at'] = $createdAt;

            $preparedItems[]['object'] = $array;
        }

        return $preparedItems;
    }
}