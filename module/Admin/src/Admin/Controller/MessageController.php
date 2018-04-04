<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;

use Admin\Form\Message\MessageForm;
use Application\Entity\EducationalProject\Category;
use Application\Entity\Message\Message;
use Application\Entity\Registration\Registration;

class MessageController extends AbstractAdminController
{
    public function indexAction()
    {
        $searchForm = new MessageForm();
        $dataAttr = $this->params()->fromQuery();
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(Message::class, $data);

        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm,
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
        $result->setTemplate('admin/message/create.phtml');
        return $result;
    }

    public function deleteAction($id)
    {
        $cat = $this->getRepository(Category::class)->find($id);
        $this->getEntityManager()->remove($cat);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Categoria excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'message']);
    }

    public function persist($data, $id = null)
    {
        $form = new MessageForm();

        if($id) {
            $message = $this->getRepository(Message::class)->find($id);
        } else {
            $message = new Message();
        }

        $noValidate = $this->params()->fromPost('no-validate', false);

        if($this->getRequest()->isPost()) {
            $form->setData($data);

            if(!$noValidate) {
                if($form->isValid()) {
                    $dataValida = $form->getData();

                    $message->setData($dataValida);
                    $this->getEntityManager()->persist($message);
                    $this->getEntityManager()->flush();

                    if($id) {
                        $this->messages()->success("Mensagem atualizada com sucesso!");
                    } else {
                        $this->messages()->flashSuccess("Mensagem criada com sucesso!");
                        return $this->redirect()->toRoute('admin/default', [
                            'controller' => 'message',
                            'action' => 'update',
                            'id' => $message->getId()
                        ]);
                    }
                }
            }
        } else {
            $form->setData($message->toArray());
        }

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'message' => $message
        ]);
    }

}