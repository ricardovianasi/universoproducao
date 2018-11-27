<?php
$env = getenv('APPLICATION_ENV') ?: 'production';

$modules = [
    'TwbBundle',
    'DoctrineModule',
    'DoctrineORMModule',
    'Util',
    'Admin',
    'Application',
    'MeuUniverso',
    'Arquivoemcartaz',
    'Cineop2017',
    'Cineop2018',
    'Cinebh',
    'Cinebh2017',
    'Mostratiradentes',
    'Mostratiradentes2017',
    'MostratiradentesSp',
    'MostratiradentesSp2017',
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
