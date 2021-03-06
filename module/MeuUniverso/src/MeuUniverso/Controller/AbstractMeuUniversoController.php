<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 15/09/2017
 * Time: 15:32
 */

namespace MeuUniverso\Controller;

use Application\Service\EntityManagerAwareInterface;
use MeuUniverso\Service\AuthenticationAwareInterface;
use Util\Controller\AbstractController;
use Zend\Authentication\AuthenticationService;

class AbstractMeuUniversoController extends AbstractController
    implements EntityManagerAwareInterface, AuthenticationAwareInterface
{
    protected $authenticationService;

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    public function setAuthenticationService($auth)
    {
        $this->authenticationService = $auth;
        return $this;
    }

}