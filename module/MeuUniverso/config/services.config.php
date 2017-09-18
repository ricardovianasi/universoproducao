<?php
namespace MeuUniverso;

use DoctrineModule\Authentication\Adapter;
use DoctrineModule\Authentication\Storage;
use Zend\Authentication\AuthenticationService;

return [
    'factories' => [
        'meuuniverso_authenticationservice' => function($serviceManager) {
            return new AuthenticationService(
                $serviceManager->get('meuuniverso_authenticationstorage'),
                $serviceManager->get('meuuniverso_authenticationadapter')
            );
        },
        'meuuniverso_authenticationadapter' => function($serviceManager) {
            $options = $serviceManager->get('Configuration');
            $options = $options['doctrine']['authentication_meuuniverso']['orm_default'];

            if(is_string($options['object_manager'])) {
                $options['object_manager'] = $serviceManager->get($options['object_manager']);
            }

            return new Adapter\ObjectRepository($options);
        },
        'meuuniverso_authenticationstorage' => function($serviceManager) {
            $options = $serviceManager->get('Configuration');
            $options = $options['doctrine']['authentication_meuuniverso']['orm_default'];

            if (is_string($options['object_manager'])) {
                $options['object_manager'] = $serviceManager->get($options['object_manager']);
            }

            if (is_string($options['storage'])) {
                $options['storage'] = $serviceManager->get($options['storage']);
            }

            return new Storage\ObjectRepository($options);
        }
    ],
    'invokables' => []
];
