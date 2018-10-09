<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/09/2018
 * Time: 09:50
 */

namespace MeuUniverso\Controller;


use Admin\Form\Proposal\WorkshopProposalForm;
use Application\Entity\Proposal\WorkshopProposal;

class WorkshopProposalController extends AbstractMeuUniversoController
{
    public function indexAction()
    {
        $proposal = new WorkshopProposal();
        $proposal->setAuthor($this->getAuthenticationService()->getIdentity());

        $return = [];
        $form = new WorkshopProposalForm($this->getEntityManager());
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()) {
                $proposal->setData($data);
                $this->getEntityManager()->persist($proposal);
                $this->getEntityManager()->flush();

                //Enviar o email para a universo

                return $this->redirect()->toRoute('meu-universo/workshop_proposal', ['action'=>'confirmacao']);
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