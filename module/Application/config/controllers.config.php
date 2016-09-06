<?php
namespace Application;

use Application\Service\EntityManagerAwareInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'factories' => [
        Controller\IndexController::class => InvokableFactory::class
    ],
    'initializers' => [
        function($instance, $sm) {
            $locator = $sm->getServiceLocator();

            if($instance instanceof EntityManagerAwareInterface) {
                $em = $locator->get('Doctrine\ORM\EntityManager');
                $instance->setEntityManager($em);
            }
        }
    ]
];