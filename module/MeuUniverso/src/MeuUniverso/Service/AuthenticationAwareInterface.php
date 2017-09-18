<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 15/09/2017
 * Time: 15:35
 */
namespace MeuUniverso\Service;

interface AuthenticationAwareInterface
{
    public function getAuthenticationService();
    public function setAuthenticationService($auth);
}