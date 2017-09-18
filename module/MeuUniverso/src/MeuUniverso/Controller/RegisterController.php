<?php
namespace MeuUniverso\Controller;

use Admin\Form\ExternalUser\PhoneForm;
use Application\Entity\City;
use Application\Entity\Phone\Phone;
use Application\Entity\User\Hash;
use Application\Entity\User\User;
use Application\Service\EntityManagerAwareInterface;
use Doctrine\Common\Collections\ArrayCollection;
use MeuUniverso\Form\NewUserForm;
use MeuUniverso\Form\UserForm;
use Util\Controller\AbstractController;
use Util\Security\Crypt;
use Zend\View\Model\ViewModel;

class RegisterController extends AbstractMeuUniversoController
{
    //Login Controller
    public function indexAction()
    {
        $this->mailService()->send();

        return [];
    }

    public function novoAction()
    {
        $return = [];
        $form = $this->getNewUserForm();
        if($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $data = $form->getData();

                //Salva o usuário
                $user = new User();
                $user->setIdentifier($data['identifier']);
                $user->setEmail($data['email']);
                $user->setName($data['name']);
                $user->setPassword(Crypt::getInstance()->generateEncryptPass($data['password']));
                $user->setConfirmedRegister(false);

                $this->getEntityManager()->persist($user);

                //Cria a hash de confirmação
                $hash = new Hash();
                $hash->setUser($user);
                $this->getEntityManager()->persist($hash);

                $this->getEntityManager()->flush();

                $link = $this->url()->fromRoute('meu-universo/register', ['action'=>'validar', 'id'=>$hash->getHash()]);

                //Enviar email de confirmação
                $msg = '<p>Olá <strong>'.$user->getName().'</strong>!</p>';
                $msg.= '<p>Seu cadastro foi realizado com sucesso. 
                    Por favor, clique no link a seguir para validar seu e-mail:</p>';
                $msg.= '<p><a href="'.$link.'">'.$link.'</a></p>';
                $msg.= '<p>Você também pode copiar o endereço e colar diretamente na barra de endereço do seu navegador.</p>';

                $to[$user->getName()] = $user->getEmail();
                $this->mailService()->simpleSendEmail($to, "Confirmação de cadastro", $msg);

                //Envia email de confirmação
                $return['success'] = true;
                $return['user'] = $user;
            }
        }

        $return['form'] = $form;

        return $return;
    }

    public function editarAction()
    {
        $form = new UserForm($this->getEntityManager());
        $phoneForm = new PhoneForm();
        $user = new User();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
            }
        }

        return [
            'user' => $user,
            'form' => $form,
            'phoneForm' => $phoneForm
        ];
    }

    public function validarAction()
    {
        //Formulários
        $form = new UserForm($this->getEntityManager());
        $form->get('email')->setAttributes([
            'disabled' => 'disabled',
            'required' => false
        ]);
        $phoneForm = new PhoneForm();

        $hash = $this->params()->fromRoute('id');

        /** @var Hash $exist */
        $exist = $this->getEntityManager()->getRepository(Hash::class)->findOneBy([
            'hash' => $hash
        ]);

        if($exist) {
            //Hash existe - verificar a validade
            $now = new \DateTime('now');
            if($now <= $exist->getValidUntil()) {
                $user = $this
                    ->getEntityManager()
                    ->getRepository(User::class)
                    ->find($exist->getUser()->getId());

                if(!$user) {
                    return ['validate' => false];
                }

                if ($this->getRequest()->isPost()) {
                    $form->setData($this->getRequest()->getPost());
                    if ($form->isValid()) {
                        $validData = $form->getData();

                        unset($validData['email']);
                        unset($validData['identifier']);

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

                        if(isset($validData['city'])) {
                            $city = $this->getRepository(City::Class)->find($validData['city']);
                            $validData['city'] = $city;
                        }

                        $user->setConfirmedRegister(true);
                        $user->setData($validData);

                        $this->getEntityManager()->persist($user);
                        $this->getEntityManager()->remove($exist);
                        $this->getEntityManager()->flush();

                        return [
                            'validate' => 'validated'
                        ];
                    }
                } else {
                    $form->setData($user->toArray());
                }

                return [
                    'form' => $form,
                    'phoneForm' => $phoneForm,
                    'user' => $user,
                    'validate' => true
                ];

            } else {
                return ['validate'=>false];
            }
        }

        return ['validate'=>false];
    }

    protected function getNewUserForm()
    {
        $form = new NewUserForm($this->getEntityManager());
        return $form;
    }
}
