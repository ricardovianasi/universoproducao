<?php
$env = getenv('APPLICATION_ENV') ?: 'production';

$modules = [
    'TwbBundle',
    'DoctrineModule',
    'DoctrineORMModule',
    'Admin',
    'Application',
    'MeuUniverso',
    'Arquivoemcartaz',
    'Cineop2017',
    'Cinebh2017',
    'Mostratiradentes',
    'MostratiradentesSp',
    'Util',
];

if($env == 'development') {
    $modules[] = 'ZendDeveloperTools';
}

return array(
    'modules' => $modules,
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            sprintf('config/autoload/{,*.}{global,%s,local}.php', $env),
        ),
    ),
);
