<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 18/09/2017
 * Time: 22:24
 */

namespace MeuUniverso\View\Helper;


use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class UserMenu extends AbstractHelper implements ServiceLocatorAwareInterface
{
    private $serviceLocator;

    public function __invoke()
    {
        $authService = null;
        if($this->getServiceLocator()->getServiceLocator()->has('meuuniverso_authenticationservice')) {
            $authService = $this->getServiceLocator()->getServiceLocator()->get('meuuniverso_authenticationservice');
        } else {
            throw new \Exception('Authentication Service not definied');
        }

        $identity = $authService->getIdentity();
        return $this->render($identity);
    }

    protected function render($user=null)
    {
        $urlHelper = $this->getServiceLocator()->get('url');

        $html = '<a class="links-action" href="%s"><i class="icon-user3"></i></a>';
        $urlUserBtn = "#";
        if($user) {
            $urlMeuCadastro = $urlHelper('meu-universo/register', ['action'=>'editar']);
            $urlMeusDependentes = $urlHelper('meu-universo/register', ['action'=>'dependentes']);
            $urlMinhasInscricoes = $urlHelper('meu-universo');
            $urlAlterarSenha = $urlHelper('meu-universo/auth', ['action'=>'alterar-senha']);
            $urlLogout = $urlHelper('meu-universo/auth', ['action'=>'sair']);

            $html = '<a class="links-action" href="%s"><i class="icon-user3"></i></a><ul class="dropdown-user">
              <li><a href="'.$urlMeuCadastro.'">Meu cadastro</a></li>
              <li><a href="'.$urlMeusDependentes.'">Meus dependentes</a></li>
              <li><a href="'.$urlMinhasInscricoes.'">Minhas inscrições</a></li>
              <li class="divider"></li>
              <li><a href="'.$urlAlterarSenha.'">Alterar senha</a></li>
              <li><a href="'.$urlLogout.'">Sair</a></li>
            </ul>';
        } else {
            $urlUserBtn = $urlHelper('meu-universo/auth');
        }

        return sprintf($html, $urlUserBtn);
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }
}