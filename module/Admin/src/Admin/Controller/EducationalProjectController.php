<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:23
 */

namespace Admin\Controller;

use Admin\Form\EducationalProject\EducationalProjectForm;
use Admin\Form\EducationalProject\EducationalProjectSearchForm;
use Admin\Form\EducationalProject\StatusModalForm;
use Application\Entity\EducationalProject\Category;
use Application\Entity\EducationalProject\EducationalProject;
use Application\Entity\File\File;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\State;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;

class EducationalProjectController extends AbstractAdminController
{
    public function indexAction()
    {
        $statusModalForm = new StatusModalForm();

        $searchForm = new EducationalProjectSearchForm($this->getEntityManager());
        $dataAttr = $this->params()->fromQuery();
        if(empty($dataAttr)) {
            $dataAttr['event'] = $this->getDefaultEvent()?$this->getDefaultEvent()->getId():null;
        }
        $searchForm->setData($dataAttr);

        if(!$searchForm->isValid()) {
            $teste = $searchForm->getMessages();
        }

        $data = $searchForm->getData();

        $items = $this->search(EducationalProject::class, $data);

        $this->getViewModel()->setVariables([
            'items' => $items,
            'searchForm' => $searchForm,
            'searchData' => $dataAttr,
            'isFiltered' => !empty($data) ? true : false,
            'statusModalForm' => $statusModalForm,
            'canEdit' => $this->getAuthenticationService()->getIdentity()->getEmail() == 'projetoseducativos@projetoseducativos.com.br'?false:true
        ]);

        return $this->getViewModel();
    }

    public function createAction($data)
    {
        return $this->persist($data);
    }

    public function updateAction($id, $data)
    {
        return $this->persist($data, $id);
    }

    public function deleteAction($id)
    {
        $cat = $this->getRepository(Category::class)->find($id);
        $this->getEntityManager()->remove($cat);
        $this->getEntityManager()->flush();

        $this->messages()->flashSuccess('Categoria excluída com sucesso.');

        return $this->redirect()->toRoute('admin/default', ['controller'=>'educational-project-category']);
    }

    public function persist($data, $id = null)
    {
        $form = new EducationalProjectForm($this->getEntityManager($this->getEntityManager()));

        if($id) {
            $project = $this->getRepository(EducationalProject::class)->find($id);
        } else {
            $project = new EducationalProject();
        }

        if($this->getRequest()->isPost()) {
            $form->setData($data);
            if($form->isValid()) {

                //Author
                $user = null;
                if(!empty($data['user'])) {
                    $user = $this
                        ->getRepository(User::class)
                        ->find($data['user']);
                }
                $project->setUser($user);
                unset($data['user']);

                $registration = null;
                if(!empty($data['registration'])) {
                    $registration = $this
                        ->getRepository(Registration::class)
                        ->find($data['registration']);
                }
                $project->setRegistration($registration);
                unset($data['registration']);

                if(!empty($data['category'])) {
                    $cat = $this->getRepository(Category::class)->find($data['category']);
                    $project->setCategory($cat);
                }
                unset($data['category']);

                $oldFiles = [];
                foreach ($project->getFiles() as $of) {
                    $oldFiles[$of->getId()] = $of;
                }
                $files = new ArrayCollection();
                if(!empty($data['files'])) {
                    foreach ($data['files'] as $f) {
                        $file = $this->populateFiles($f);
                        if($file) {
                            $files->add($file);
                            unset($oldFiles[$file->getId()]);
                        }
                    }
                }
                foreach ($oldFiles as $key=>$fileToRemove) {
                    $this->getEntityManager()->remove($fileToRemove);
                }
                unset($data['image']);
                unset($data['files']);
                $project->setFiles($files);

                $project->setData($data);
                $this->getEntityManager()->persist($project);
                $this->getEntityManager()->flush();

                if($id) {
                    $this->messages()->success("Projeto atualizado com sucesso!");
                } else {
                    $this->messages()->flashSuccess("Projeto criado com sucesso!");
                    return $this->redirect()->toRoute('admin/default', [
                        'controller' => 'educational-project',
                        'action' => 'update',
                        'id' => $project->getId()
                    ]);
                }
            }
        }

        $form->setData($project->toArray());

        return $this->getViewModel()->setVariables([
            'form' => $form,
            'project' => $project,
            'canEdit' => $this->getAuthenticationService()->getIdentity()->getEmail() == 'projetoseducativos@projetoseducativos.com.br'?false:true
        ]);
    }

    protected function populateFiles($data, $isDefault=false)
    {
        $media = null;
        if(!empty($data['id'])) {
            $media = $this->getRepository(File::class)->find($data['id']);
        }

        if(!$media) {
            $media = new File();
        }

        if(!empty($data['file']['name'])) {
            $mediaFile = $data["file"];
            if(!empty($mediaFile['name'])) {
                $file = $this->fileManipulation()->moveToRepository($mediaFile);
                $media->setSrc($file['new_name']);
            }
        } elseif(empty($media->getId())) {
            return;
        }

        return $media;
    }

    public function exportAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $id = $this->params()->fromRoute('id');
        $item = $this->getRepository(EducationalProject::class)->find($id);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($item);

        return $this->prepareReport($preparedItems, 'educational_project' ,'pdf');
    }

    public function exportListAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(EducationalProject::class, $dataAttr, ['createdAt' => 'DESC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'educational_project_list' ,'xlsx');
    }

    protected function prepareItemsForReports($items)
    {
        if(!is_array($items)) {
            $items = [$items];
        }

        $preparedItems = [];
        foreach ($items as $obj) {

            $itemArray = $obj->toArray();
            unset($itemArray['medias']);
            unset($itemArray['updated_at']);
            unset($itemArray['registration']);
            unset($itemArray['default_input_filters']);
            unset($itemArray['event']);
            unset($itemArray['files']);

            //Author
            $author = [
                'user_id' => $obj->getUser()->getId(),
                'user_name' => $obj->getUser()->getName(),
                'user_email' => $obj->getUser()->getEmail(),
                'user_address' => $obj->getUser()->getFullAddress()
            ];
            $phones = [];
            foreach ($obj->getUser()->getPhones() as $phone) {
                $phones[] = implode('|', $phone->_toArray());
            }
            $author['user_phones'] = implode(';', $phones);

            $itemArray = $itemArray+$author;
            unset($itemArray['user']);

            //Event
            $itemArray['event_name'] = $obj->getEvent()->getFullName();

            //state
            if(!empty($itemArray['state']) && $itemArray['state'] instanceof State) {
                $state = $itemArray['state'];
                $itemArray['state'] = $state->getName();
            }

            //category
            if(!empty( $itemArray['category']) &&  $itemArray['category'] instanceof Category) {
                $category = $itemArray['category'];
                $itemArray['category'] = $category->getName();
            }

            $itemArray['status'] = Status::get($obj->getStatus());

            //Created At
            $createdAt = "";
            if($obj->getCreatedAt() instanceof \DateTime) {
                $createdAt = $obj->getCreatedAt()->format('d/m/Y H:i:s');
            }
            $itemArray['created_at'] = $createdAt;

            $preparedItems[] = ['object'=>$itemArray];
        }
        return $preparedItems;
    }

    public function statusAction()
    {
        if(!$this->getRequest()->isPost()) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'educational-project',
                'action' => 'index'
            ]);
        }

        $data = $this->getRequest()->getPost()->toArray();
        if(empty($data['status'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'educational-project',
                'action' => 'index'
            ]);
        }

        if(empty($data['filter'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'educational-project',
                'action' => 'index'
            ]);
        }

        $status = $data['status'];
        parse_str(urldecode($data['filter']), $filter);

        if(empty($filter['selected'])) {
            $this->messages()->flashError("Erro ao processar solicitação.");
            return $this->redirect()->toRoute('admin/default', [
                'controller' => 'educational-project',
                'action' => 'index'
            ]);
        }

        $selectedItens = [];
        if($filter['selected'] == 'all') {
            $selectedItens = $this->search(EducationalProject::class, $filter, [], true);
        } else {
            $selected = explode(',', $filter['selected']);
            if(!$selected) {
                $this->messages()->flashError("Erro ao processar solicitação.");
                return $this->redirect()->toRoute('admin/default', [
                    'controller' => 'educational-project',
                    'action' => 'index'
                ]);
            }

            $qb = $this
                ->getRepository(EducationalProject::class)
                ->createQueryBuilder('m');

            $selectedItens = $qb
                ->andWhere($qb->expr()->in('m.id', ':arrayId'))
                ->setParameter('arrayId', $selected)
                ->getQuery()
                ->getResult();
        }

        $contItensChange = 0;
        foreach ($selectedItens as $subscription) {
            if($subscription) {
                $subscription->setStatus($status);
                $this->getEntityManager()->persist($subscription);

                $contItensChange++;
            }
        }

        $this->getEntityManager()->flush();
        $this->messages()->flashSuccess("Status alterado com suscesso!");
        return $this->redirect()->toRoute('admin/default', [
            'controller' => 'educational-project',
            'action' => 'index',
        ], ['query'=>$filter]);

    }

    public function comunicadosAction()
    {
        $this->getViewModel()->setTerminal(true);

        $items = $this
            ->getRepository(EducationalProject::class)
            ->createQueryBuilder('m')
            ->andWhere('m.event = :idEvent')
            ->setParameters([
                'status' => 'not_selected',
                'idEvent' => 1085
            ])
            ->getQuery()
            ->getResult();

        var_dump(count($items)); exit();
        $count = 0;
        foreach ($items as $item) {
            /** @var Movie $item */
            $item = new EducationalProject();

            $msg = "<p>Prezado (a) ".$item->getUser()->getName().",</p>";
            $msg.= "<p>Comunicamos que o projeto  <strong>".$item->getTitle() ."</strong> não foi selecionado para a 13ª CineOP - Mostra de Cinema de Ouro Preto.</p>";
            $msg.= "<p>Esclarecemos que os critérios que baseiam a seleção de projetos são múltiplos e podem variar de acordo com o perfil e tema abordado no evento.</p>";
            $msg.= "<p>Se algum projeto não pertence à lista final de selecionados, é porque não se enquadrou nos critérios e/ou por falta de espaço na grade de programação devido a limitação do período de duração do evento. </p>";
            $msg.= "<p>Agradecemos seu interesse e esperamos contar com sua participação nas próximas edições.</p>";
            $msg.= "<p>A programação da CineOP é gratuita e estará disponível no site <a href='http://www.cineop.com.br'>www.cineop.com.br</a>, a partir do dia 23 de maio. Aproveitamos para convidá-lo à participar das outras atividades da 13ª CineOP – debates, shows, cortejos e rodas de conversa.</p>";
            $msg.= "<p>Atenciosamente,<br />Coordenação – CineOP</p>";

            //$to[$item->getAuthor()->getName()] = 'ricardovianasi@gmail.com';
            /** @var \SendGrid\Response $return */
            $return = $this->mailService()->simpleSendEmail(
                //[$item->getUser()->getName()=>$item->getUser()->getEmail()],
                [$item->getAuthor()->getName()=>'ricardovianasi@gmail.com'],
                'Projetos - 13ª CineOP - Mostra de Cinema de Ouro Preto', $msg);

            $count++;
            echo "$count - Nome: " . $item->getUser()->getName();
            echo "<br />Email: " . $item->getUser()->getEmail();
            echo "<br />Projeto: " . $item->getTitle();
            if($return->statusCode() == 202) {
                echo "<br /><b>******************-SUCESSO-******************</b><br /><br />";

            } else {
                echo "<b>******************-ERRO-******************</b><br /><br />";
                $item->setComunicadoEnviado(0);
            }
        }

        return $this->getViewModel();
    }
}