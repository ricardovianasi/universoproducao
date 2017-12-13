<?php
namespace MeuUniverso;

use Application\Service\EntityManagerAwareInterface;
use MeuUniverso\Service\AuthenticationAwareInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'factories' => [
        Controller\IndexController::class                   => InvokableFactory::class,
        Controller\AuthController::class                    => InvokableFactory::class,
        Controller\RegisterController::class                => InvokableFactory::class,
        Controller\MovieRegistrationController::class       => InvokableFactory::class,
        Controller\WorkshopRegistrationController::class    => InvokableFactory::class,
        Controller\ErrorController::class                   => InvokableFactory::class,
    ],
    'initializers' => [
        function($instance, $sm) {
            $locator = $sm->getServiceLocator();

            if($instance instanceof EntityManagerAwareInterface) {
                $em = $locator->get('Doctrine\ORM\EntityManager');
                $instance->setEntityManager($em);
            }

            if($instance instanceof AuthenticationAwareInterface) {
                $authService = $locator->get('meuuniverso_authenticationservice');
                $instance->setAuthenticationService($authService);
            }
        }
    ]
];