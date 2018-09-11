<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/09/2018
 * Time: 09:50
 */

namespace MeuUniverso\Controller;


use Admin\Form\Proposal\ArtisticProposalForm;
use Application\Entity\Proposal\ArtisticProposal;
use Application\Entity\Proposal\ArtisticProposalCategory;

class ArtisticProposalController extends AbstractMeuUniversoController
{
    public function indexAction()
    {
        $proposal = new ArtisticProposal();
        $return = [];
        $form = new ArtisticProposalForm($this->getEntityManager());
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()) {

                if(!empty($data['category'])) {
                    $cat = $this
                        ->getRepository(ArtisticProposalCategory::class)
                        ->find($data['category']);
                    $proposal->setCategory($cat);
                }
                unset($data['category']);

                $proposal->setData($data);
                $this->getEntityManager()->persist($proposal);
                $this->getEntityManager()->flush();

                //Enviar o email para a universo

                return $this->redirect()->toRoute('meu-universo/artistic_proposal', ['action'=>'confirmacao']);
            }
        }

        $return['form'] = $form;
        return $return;
    }

    public function confirmacaoAction()
    {
        return [];
    }
}