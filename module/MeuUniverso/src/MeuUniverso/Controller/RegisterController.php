<?php
namespace MeuUniverso\Controller;

use Admin\Form\ExternalUser\DependentForm;
use Admin\Form\ExternalUser\PhoneForm;
use Application\Entity\City;
use Application\Entity\Phone\Phone;
use Application\Entity\User\Hash;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use MeuUniverso\Form\NewUserForm;
use MeuUniverso\Form\UserForm;
use MeuUniverso\Form\ValidateUserForm;
use Util\Security\Crypt;
use Zend\I18n\Filter\Alnum;

class RegisterController extends AbstractMeuUniversoController
{
    //Login Controller
    public function indexAction()
    {
        $this->mailService()->send();

        return [];
    }

    /*public function novoAction()
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
                $user->getUpdateRegisterRequired(true);

                $this->getEntityManager()->persist($user);

                //Cria a hash de confirmação
                $hash = new Hash();
                $hash->setUser($user);
                $hash->setType(Hash::TYPE_EMAIL_VALIDATION);
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
    }*/

    public function reEnviarLinkAction()
    {
        $return = [];
        if($this->getRequest()->isPost()) {
            $idFilter = new Alnum();

            $email = $this->params()->fromPost('login');
            $identifier = $idFilter($this->params()->fromPost('identifier'));

            if(empty($email) || empty($identifier)) {
                return [
                    'error' => true
                ];
            }

            /** @var User $user */
            $user = $this->getEntityManager()->getRepository(User::class)->findOneBy([
                'email' => $email,
                'identifier' => $identifier
            ]);

            if(!$user) {
                return [
                    'error' => true
                ];
            }

            $hashs = $this->getEntityManager()->getRepository(Hash::class)->findBy([
                'user' => $user->getId(),
                'type' => Hash::TYPE_EMAIL_VALIDATION
            ]);
            foreach ($hashs as $h) {
                $this->getEntityManager()->remove($h);
            }

            //Cria a hash de confirmação
            $hash = new Hash();
            $hash->setUser($user);
            $hash->setType(Hash::TYPE_EMAIL_VALIDATION);
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

        return $return;
    }

    public function editarAction()
    {
        $identity = $this->getAuthenticationService()->getIdentity();
        $user = $this->getRepository(User::class)->find($identity->getId());

        $form = new UserForm($this->getEntityManager(), $user->getType());
        $phoneForm = new PhoneForm();

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost()->toArray();
            $data['identifier'] = $user->getIdentifier();

            $form->setData($data);
            if ($form->isValid()) {
                $validData = $form->getData();
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

                $user->setUpdateRegisterRequired(false);
                $user->setData($validData);

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                $msg = '<p>Seu cadastro foi atualizado com sucesso!';
                $this->meuUniversoMessages()->flashSuccess($msg);

                return $this->redirect()->toRoute('meu-universo/default');
            }
        } else {
            $form->setData($user->toArray());
        }

        return [
            'user' => $user,
            'form' => $form,
            'phoneForm' => $phoneForm,
            'requiredUpdate' => $this->params()->fromQuery('atualizacao-necessaria', false)
        ];
    }

    public function novoAction()
    {
        //Formulários
        $form = new ValidateUserForm($this->getEntityManager());
        $phoneForm = new PhoneForm();
        $user = new User;
        $user->setOrigin(User::ORIGIN_MEUUNIVERSO);
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()) {
                $validData = $form->getData();

                $phones = new ArrayCollection();
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

                $user->setPassword(Crypt::getInstance()->generateEncryptPass($validData['password']));
                unset($validData['password']);

                $user->setConfirmedRegister(true);
                $user->setUpdateRegisterRequired(false);
                $user->setData($validData);

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                //Enviar email de confirmação
                $msg = '<p>Olá <strong>'.$user->getName().'</strong>!</p>';
                $msg.= '<p>Seu cadastro foi realizado com sucesso!</p>';

                $to[$user->getName()] = $user->getEmail();
                $this->mailService()->simpleSendEmail($to, "Confirmação de cadastro", $msg);

            }
        } else {
            $form->setData($user->toArray());
        }

        return [
            'form' => $form,
            'phoneForm' => $phoneForm,
            'user' => $user,
            'validate' => true,
            'allowPass' => true
        ];
    }

    public function dependentesAction()
    {
        $form = new DependentForm();

        $identity = $this->getAuthenticationService()->getIdentity();
        $user = $this->getRepository(User::class)->find($identity->getId());

        if($this->getRequest()->isPost()) {

        }

        return [
            'form' => $form,
            'user' => $user
        ];
    }

    protected function getNewUserForm()
    {
        $form = new NewUserForm($this->getEntityManager());
        return $form;
    }
}
