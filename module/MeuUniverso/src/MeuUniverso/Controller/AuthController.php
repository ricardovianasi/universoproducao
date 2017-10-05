<?php
namespace MeuUniverso\Controller;

use Application\Entity\User\Hash;
use Application\Entity\User\User;;

use MeuUniverso\Form\ChangePasswordForm;
use Util\Security\Crypt;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractMeuUniversoController
{
    //Login Controller
    public function indexAction()
    {
        if($this->getRequest()->isPost()) {
            $data = (array) $this->getRequest()->getPost();

            $authService = $this->getAuthenticationService();

            $adapter = $authService->getAdapter();
            $adapter->setIdentity($data['login']);
            $adapter->setCredential($data['password']);

            $authResult = $authService->authenticate();

            if ($authResult->isValid()) {

                /** @var User $user */
                $user = $this->getAuthenticationService()->getIdentity();

                if(!$user->getConfirmedRegister()) {
                    $this->getAuthenticationService()->clearIdentity();
                    return [
                        'error' => true,
                        'reason' => 'user_not_confirmed_register'
                    ];
                }

                $session = new Container();
                if($session->offsetExists('last_url_accessed_before_login')) {
                    $redirect = $session->offsetGet('last_url_accessed_before_login');
                    $session->offsetUnset('last_url_accessed_before_login');
                    return $this->redirect()->toUrl($redirect);
                } else {
                    return $this->redirect()->toRoute('meu-universo/default');
                }
            }

            return [
                'error' => true
            ];
        }
        return [];
    }

    public function recuperarSenhaAction()
    {
        if($this->getRequest()->isPost()) {
            $data = (array) $this->getRequest()->getPost();
            if(empty($data['login'])) {
                return [
                    'error' => true,
                    'reason' => 'data_not_sent'
                ];
            }

            $user = $this
                ->getEntityManager()
                ->getRepository(User::class)
                ->findOneBy([
                    'email' => $data['login']
                ]);

            if(!$user) {
                return [
                    'error' => true,
                    'reason' => 'user_not_found',
                    'login' => $data['login']
                ];
            }

            //Cria a hash de confirmação
            $hash = new Hash();
            $hash->setUser($user);
            $this->getEntityManager()->persist($hash);
            $this->getEntityManager()->flush();

            $link = $this->url()->fromRoute('meu-universo/auth', ['action'=>'alterar-senha', 'id'=>$hash->getHash()]);

            //Enviar email de confirmação
            $msg = '<p>Olá <strong>'.$user->getName().'</strong>!</p>';
            $msg.= '<p>Esqueceu sua senha? Não tem problema. Crie uma nova clicando no botão abaixo: </p>';
            $msg.= '<p style="text-align: center">'.$this->mailService()->generateButton('Gerar nova senha', $link).'</p>';
            $msg.= '<p>Se o botão não funcionar, copie e cole o seguinte link em seu navegador:</p>
                <p><a href="'.$link.'">'.$link.'</a></p>';

            $to[$user->getName()] = $user->getEmail();
            $this->mailService()->simpleSendEmail($to, "Recuperar senha", $msg);

            return [
                'error' => false,
                'reason' => 'email_sent'
            ];
        }
    }

    public function alterarSenhaAction()
    {
        //Verificar usuário logado ou hash de alteração
        if($hash = $this->params()->fromRoute('id'))  {
            $form = new ChangePasswordForm();

            $hash = $this->params()->fromRoute('id');

            /** @var Hash $exist */
            $exist = $this->getEntityManager()->getRepository(Hash::class)->findOneBy([
                'hash' => $hash
            ]);

            if($exist) {
                //Hash existe - verificar a validade
                $now = new \DateTime('now');
                if ($now <= $exist->getValidUntil()) {
                    $user = $this
                        ->getEntityManager()
                        ->getRepository(User::class)
                        ->find($exist->getUser()->getId());

                    if (!$user) {
                        return [
                            'error' => true,
                            'reason' => 'user_not_found'
                        ];
                    }
                }
            } else {
                return [
                    'error' => true,
                    'reason' => 'user_not_found'
                ];
            }
        } elseif($this->getAuthenticationService()->hasIdentity()) {
            //Usuário logado
            $form = new ChangePasswordForm(true);

            $identity = $this->getAuthenticationService()->getIdentity();
            $user = $this->getRepository(User::class)->find($identity->getId());
            if(!$user) {
                return [
                    'error' => true,
                    'reason' => 'user_not_found'
                ];
            }

        } else {
            return $this->redirect()->toRoute('meu-universo/auth');
        }

        if($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $validData = $form->getData();
                if($this->getAuthenticationService()->hasIdentity()) {
                    if(!Crypt::getInstance()->testPass($validData['old_password'], $user->getPassword())) {
                        return [
                            'error' => true,
                            'reason' => 'old_password_not_match',
                            'form' => $form
                        ];
                    }
                }

                $user->setPassword(Crypt::getInstance()->generateEncryptPass($validData['password']));
                $user->setChangePasswordRequired(false);
                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                $this->getAuthenticationService()->clearIdentity();

                return [
                    'error' => false,
                    'reason' => 'password_change'
                ];
            }
        }

        return [
            'form' => $form,
            'requiredUpdate' => $this->params()->fromQuery('atualizacao-necessaria', false)
        ];
    }

    public function sairAction()
    {
        $this->getAuthenticationService()->clearIdentity();
		return $this->redirect()->toRoute('meu-universo/auth');
    }
}
