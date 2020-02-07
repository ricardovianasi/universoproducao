<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 04/12/2015
 * Time: 09:43
 */

namespace Admin\Controller;

use Admin\Form\ExternalUser\CompanyForm;
use Admin\Form\ExternalUser\DependentForm;
use Admin\Form\ExternalUser\PhoneForm;
use Admin\Form\ExternalUser\UserContactForm;
use Admin\Form\ExternalUser\UserContactFormSearch;
use Admin\Form\ExternalUser\UserForm;
use Application\Entity\City;
use Application\Entity\Phone\Phone;
use Application\Entity\User\Category;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Validator\NoObjectExists;
use Zend\View\Model\JsonModel;

class ContactController extends AbstractAdminController
    implements CrudInterface
{
	public function indexAction()
	{
		$searchForm = new UserContactFormSearch($this->getEntityManager());
		$dataAttr = $this->params()->fromQuery();

		unset($dataAttr['form-reloaded']);

		$searchForm->setData($dataAttr);
		$searchForm->isValid();

		$users = $this->search(User::class, $searchForm->getData(), ['name'=>'ASC']);

		$this->getViewModel()->setVariables([
			'searchForm' => $searchForm,
			'users' => $users,
            'searchData' => $dataAttr
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

	public function persist($data, $id = null)
	{
		$form = new UserContactForm($this->getEntityManager());
		$phoneForm = new PhoneForm();
		$dependentForm = new DependentForm();

		if($id) {
			$user = $this->getRepository(User::class)->find($id);
		} else {
			$user = new User();
			$user->setOrigin(User::ORIGIN_CONTATO);
		}

		if($this->getRequest()->isPost()) {

		    //Validação de cpf/cnpj
            $userId = $user->getIdentifier();
            $newId = $data['identifier'];
            if($userId != $newId) {
                $identifierValidator = new NoObjectExists([
                    'object_repository' => $this->getEntityManager()->getRepository(User::class),
                    'fields'            => 'identifier',
                    'messages' => [
                        'objectFound' => 'Este identificador já existe em nossa base de dados',
                    ],
                ]);
                $form
                    ->getInputFilter()
                    ->get('identifier')
                    ->getValidatorChain()
                    ->attach($identifierValidator);
            }

			$form->setData($data);

			if($form->isValid()) {
			    //Salva o usuário
				$validData = $form->getData();

				$phones = new ArrayCollection();
				foreach ($user->getPhones() as $ph) {
				    $this->getEntityManager()->remove($ph);
                }
				if(!empty($validData['phones'])) {
                    foreach ($validData['phones'] as $ph) {
                        $phone = new Phone($ph);
                        $phone->setUser($user);
                        $phones->add($phone);
                    }
                }
                unset($validData['phones']);
                $user->setPhones($phones);

                $dependentsToRemove = [];
                foreach ($user->getDependents() as $depRemove) {
                    $dependentsToRemove[$depRemove->getId()] = $depRemove;
                }

                if(isset($validData['city'])) {
                    $city = $this->getRepository(City::Class)->find($validData['city']);
                    $validData['city'] = $city;
                }

                if(isset($validData['category'])) {
                    $category = $this->getRepository(Category::class)->find($validData['category']);
                    $validData['category'] = $category;
                }

                if(isset($validData['subcategory'])) {
                    $subcategory = $this->getRepository(Category::class)->find($validData['subcategory']);
                    $validData['subcategory'] = $subcategory;
                }

                $user->setData($validData);

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

				if($id) {
					$this->messages()->success("Usuário atualizado com sucesso!");
                    $this->userLog()->log($user, 'Cadastro do usuário atualizado');
				} else {
					$this->messages()->flashSuccess("Usuário criada com sucesso!");
                    $this->userLog()->log($user, 'Cadastro do usuário criado');
					return $this->redirect()->toRoute('admin/default', [
						'controller' => 'contact',
						'action' => 'update',
						'id' => $user->getId()
					]);
				}
			}
		} else {
			$form->setData($user->toArray());
		}

		return $this->getViewModel()->setVariables([
			'form' => $form,
            'phoneForm' => $phoneForm,
			'dependentForm' => $dependentForm,
			'user' => $user,
            'data' => $data
		]);
	}

	public function deleteAction($id)
	{
		$user = $this->getRepository(User::class)->find($id);

		$this->getEntityManager()->remove($user);
		$this->getEntityManager()->flush();

		$this->messages()->flashSuccess('O usuário foi excluído com sucesso.');

		return $this->redirect()->toRoute('admin/default', ['controller'=>'user']);
	}


    public function subcategoryAction()
    {
        $jsonModel = new JsonModel();
        $jsonModel->setTerminal(true);

        try {

            $category = $this->params()->fromPost('category');
            if (!$category) {
                $jsonModel->error = "Categoria não informado!";
            }

            $subcategory = $this->getRepository(Category::class)->findBy(['parent'=>$category], ['name'=>'ASC']);
            $subcategoryArray[] = '<option>Selecione</option>';
            foreach($subcategory as $c) {
                $subcategoryArray[] = '<option value="'.$c->getId().'">'.$c->getName().'</option>';
            }

            $jsonModel->subcategories = $subcategoryArray;

        } catch(\Exception $e) {
            $jsonModel->error = 'Não foi possível localizar as categorias. Por favor, tente novamente.';
        }

        return $jsonModel;
    }

    public function exportListAction()
    {
        //recupera os itens
        $dataAttr = $this->params()->fromQuery();
        $items = $this->search(User::class, $dataAttr, ['name' => 'ASC'], true);

        //criar um arquivo json
        $preparedItems = $this->prepareItemsForReports($items);
        return $this->prepareReport($preparedItems, 'user_contact_list' ,'xlsx');
    }

    protected function prepareItemsForReports($items)
    {
        if (!is_array($items)) {
            $items = [$items];
        }

        $preparedItems = [];
        foreach ($items as $obj) {
            $itemArray = $obj->toArray();

            unset($itemArray['password']);
            unset($itemArray['confirmed_register']);
            unset($itemArray['change_password_required']);
            unset($itemArray['update_register_required']);
            unset($itemArray['parent']);
            unset($itemArray['dependents']);
            unset($itemArray['logs']);
            unset($itemArray['created_at']);
            unset($itemArray['updated_at']);
            unset($itemArray['default_input_filters']);

            $itemArray['tag'] = "";
            if($obj->getTag()) {
                $itemArray['tag'] = 'Sim';
            } else {
                $itemArray['tag'] = 'Não';
            }

            $itemArray['status'] = "";
            if($obj->getStatus()) {
                $itemArray['status'] = 'Sim';
            } else {
                $itemArray['status'] = 'Não';
            }

            $phones = [];
            foreach ($obj->getPhones() as $phone) {
                $phones[] = implode('|', $phone->_toArray());
            }
            $itemArray['phones'] = implode(';', $phones);

            if($obj->getOrigin() == User::ORIGIN_MEUUNIVERSO) {
                $itemArray['origin'] = 'Meu Universo';
            } else {
                $itemArray['origin'] = 'SGC';
            }

            $itemArray['category'] = "";
            $itemArray['subcategory'] = "";
            if($obj->getCategory()) {
                $itemArray['category'] = $obj->getCategory()->getName();
            }
            if($obj->getSubcategory()) {
                $itemArray['subcategory'] = $obj->getSubcategory()->getName();
            }

            $itemArray['state'] = $itemArray['city'] = "";
            if($obj->getCity()) {
                $itemArray['city'] = $obj->getCity()->getName();
                $itemArray['state'] = $obj->getCity()->getState()->getName();
            }

            $itemArray['birth_date'] = "";
            if($obj->getBirthDate()) {
                if($obj->getBirthDate() instanceof \DateTime) {
                    $itemArray['birth_date'] = $obj->getBirthDate()->format('d/m/Y');
                }
            }

            $itemArray['gender'] = "";
            if($obj->getGender() == 'm') {
                $itemArray['gender'] = 'Masculino';
            } elseif($obj->getGender() == 'f') {
                $itemArray['gender'] = 'Feminino';
            } else {
                $itemArray['gender'] = "";
            }


            $preparedItems[] = ['object'=>$itemArray];
        }

        return $preparedItems;
    }
}