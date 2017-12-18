<?php
namespace MeuUniverso\Controller;

use Admin\Form\ExternalUser\DependentForm;
use Admin\Form\ExternalUser\PhoneForm;
use Application\Entity\City;
use Application\Entity\Phone\Phone;
use Application\Entity\User\Hash;
use Application\Entity\User\User;
use Application\Service\EntityManagerAwareInterface;
use Doctrine\Common\Collections\ArrayCollection;
use MeuUniverso\Form\NewUserForm;
use MeuUniverso\Form\UserForm;
use MeuUniverso\Form\ValidateUserForm;
use Util\Controller\AbstractController;
use Util\Security\Crypt;
use Zend\I18n\Filter\Alnum;
use Zend\View\Model\ViewModel;

class DependentsController extends AbstractMeuUniversoController
{
    public function indexAction()
    {
        $identity = $this->getAuthenticationService()->getIdentity();
        $user = $this->getRepository(User::class)->find($identity->getId());
        return [
            'user' => $user
        ];
    }

    public function novoAction()
    {
        return $this->persist();
    }

    public function editarAction()
    {
        return $this->persist();
    }

    public function persist()
    {
        $identity = $this->getAuthenticationService()->getIdentity();
        $user = $this->getRepository(User::class)->find($identity->getId());

        if($id = $this->params()->fromRoute('id')) {
            $dep = $this->getRepository(User::class)->findOneBy([
                'parent' => $user->getId(),
                'id' => $id
            ]);
        } else {
            $dep = new User();
            $dep->setParent($user);
        }

        $form = new DependentForm();
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if($form->isValid()) {
                $dep->setData($data);

                $this->getEntityManager()->persist($dep);
                $this->getEntityManager()->flush();

                $msg = '<p>Dependente cadastrado com sucesso!';
                $this->meuUniversoMessages()->flashSuccess($msg);

                return $this->redirect()->toRoute('meu-universo/dependents');
            }
        } else {
            $form->setData($dep->toArray());
        }

        return [
            'form' => $form
        ];
    }

    public function excluirAction()
    {
        $identity = $this->getAuthenticationService()->getIdentity();
        $id = $this->params()->fromRoute('id');

        $dep = $this->getRepository(User::class)->findOneBy([
            'parent' => $identity->getId(),
            'id' => $id
        ]);

        if($dep) {
            $this->getEntityManager()->remove($dep);
            $this->getEntityManager()->flush();
            $msg = '<p>Dependente excluido com sucesso!';
            $this->meuUniversoMessages()->flashSuccess($msg);
        } else {
            $msg = '<p>Dependente não localizado. Por favor, tente novamente!';
            $this->meuUniversoMessages()->flashError($msg);
        }

        return $this->redirect()->toRoute('meu-universo/dependents');
    }
}
