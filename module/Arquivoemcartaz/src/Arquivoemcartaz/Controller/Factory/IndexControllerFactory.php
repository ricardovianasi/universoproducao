<?php

namespace Arquivoemcartaz\Controller\Factory;

use Arquivoemcartaz\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm = $serviceLocator->getServiceLocator();
        $doctrineEntityManager = $sm->get('Doctrine\ORM\EntityManager');

        $controller = IndexController();
        $controller->setEntityManager($doctrineEntityManager);
        return $controller;
    }
}