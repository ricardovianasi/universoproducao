<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 20/02/2016
 * Time: 11:35
 */

namespace Admin\Controller;

use Admin\Form\Proposal\WorkshopProposalForm;
use Application\Entity\Proposal\WorkshopProposal;

class WorkshopProposalController extends AbstractAdminController
	implements CrudInterface

{
	public function indexAction()
	{
		$searchForm = new WorkshopProposalForm();
		$dataAttr = $this->params()->fromQuery();
		$searchForm->setData($dataAttr);
		$searchForm->isValid();

		$items = $this->search(WorkshopProposal::class, $searchForm->getData());

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
        $result->setTemplate('admin/workshop-proposal/create.phtml');
        return $result;
	}

	public function deleteAction($id)
	{
		$proposal = $this->getRepository(WorkshopProposal::class)->find($id);

		$this->getEntityManager()->remove($proposal);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('Proposta excluída com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'workshop-proposal']);
	}

	public function persist($data, $id = null)
	{
		$form = new WorkshopProposalForm($this->getEntityManager());
		if($id) {
			$proposal = $this->getRepository(WorkshopProposal::class)->find($id);
		} else {
            $proposal = new WorkshopProposal();
        }

		if($this->getRequest()->isPost()) {
			$form->setData($data);
			if($form->isValid()) {
				$validData = $form->getData();

                $user = null;
                if($this->getRequest()->isPost()) {
                    if (!empty($validData['author'])) {
                        $user = $this
                            ->getRepository(\Application\Entity\User\User::class)
                            ->find($validData['author']);
                    }
                }
                $proposal->setAuthor($user);
                unset($validData['user']);

				$proposal->setData($validData);
				$this->getEntityManager()->persist($proposal);
				$this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Proposta atualizada com sucesso!");
				} else {
					$this->messages()->flashSuccess("Proposta criado com sucesso!");
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'workshop-proposal',
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
        $item = $this->getRepository(WorkshopProposal::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);

        return $this->prepareReport($preparedItems, 'workshop-proposal' ,'pdf');
    }

    public function exportListAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(WorkshopProposal::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'workshop-proposal-list' ,'xlsx');
    }

    protected function prepareItemsForReports($items)
    {
        if(!is_array($items)) {
            $items = [$items];
        }

        $preparedItems = [];
        foreach ($items as $obj) {
//            $obj = new ArtisticProposal();
            $array = $obj->toArray();
            unset($array['updated_at']);
            unset($array['default_input_filters']);

            //Author
            $author = [
                'author_id' => $obj->getAuthor() ? $obj->getAuthor()->getId() : "",
                'author_name' => $obj->getAuthor() ? $obj->getAuthor()->getName() : "",
                'author_email' => $obj->getAuthor() ? $obj->getAuthor()->getEmail() : "",
                'author_address' => $obj->getAuthor() ? $obj->getAuthor()->getFullAddress() : ""
            ];
            $phones = [];
            if($obj->getAuthor()) {
                foreach ($obj->getAuthor()->getPhones() as $phone) {
                    $phones[] = implode('|', $phone->_toArray());
                }
                $author['author_phones'] = implode(';', $phones);

                $array = $array+$author;
            }
            unset($array['author']);

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