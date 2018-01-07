<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;

use Admin\Form\Art\ArtCategoryForm;
use Admin\Form\Seminar\SeminarThematicForm;
use Application\Entity\Art\Category;
use Application\Entity\Seminar\Thematic;

class SeminarThematicController extends AbstractAdminController
{
    public function indexAction()
    {
        $form = new SeminarThematicForm();
        if($this->getRequest()->isPost()) {
            $data  = $this->getRequest()->getPost();
            $form->setData($data);
            if($form->isValid()) {
                if($this->params()->fromRoute('id')) {
                    $thematic = $this
                        ->getRepository(Thematic::class)
                        ->find($this->params()->fromRoute('id'));
                } else {
                    $thematic = new Thematic();
                }

                $thematic->setData($form->getData());

                $this->getEntityManager()->persist($thematic);
                $this->getEntityManager()->flush();
                if($this->params()->fromRoute('id')) {
                    $this->messages()->flashSuccess("Temática atualizada com sucesso!");
                    return $this->redirect()->toRoute('admin/default', ['controller'=>'seminar-thematic']);
                } else {
                    $this->messages()->success("Temática criada com sucesso!");
                }
            }
        } elseif($id = $this->params()->fromRoute('id')) {
            $thematic = $this
                ->getRepository(Thematic::class)
                ->find($id);

            $form->setData($thematic->toArray());
        }

        $items = $this->getRepository(Thematic::class)->findBy([], ['name'=>'ASC']);
        $this->getViewModel()->setVariables([
            'items' => $items,
            'form' => $form
        ]);

        return $this->getViewModel();
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $thematic = $this
            ->getRepository(Thematic::class)
            ->find($id);

        if($thematic) {
            $this->getEntityManager()->remove($thematic);
            $this->getEntityManager()->flush();

            $this->messages()->flashSuccess('Temática excluída com sucesso.');
        } else {
            $this->messages()->flashError('Erro ao localizar temática. Por favor, tente novamente.');
        }

        return $this->redirect()->toRoute('admin/default', ['controller'=>'seminar-thematic']);
    }

}