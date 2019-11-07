<?php
$env = getenv('APPLICATION_ENV') ?: 'production';

$modules = [
    'TwbBundle',
    'DoctrineModule',
    'DoctrineORMModule',
    'Util',
    'Admin',
    'Application',
    'Brasilcinemundi',
    'MeuUniverso',
    'Arquivoemcartaz',
    'Cineop2018',
    'Cineop2019',
    'Cinebh2019',
    'Cinebh',
    'Mostratiradentes',
    'MostratiradentesSp',
    'Mostratiradentes2020',

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
