<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 12/09/2017
 * Time: 11:46
 */
namespace Application\Controller\Plugin\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        if($services->has('mailService')) {
            return $services->get('mailService');
        }

        return null;
    }

}