<?php
$env = getenv('APPLICATION_ENV') ?: 'production';
return array(
    'modules' => array(
        'ZendDeveloperTools',
        'TwbBundle',
        'DoctrineModule',
        'DoctrineORMModule',
        'Util',
        'Admin',
        'Application',
        'Arquivoemcartaz',
        'Cineop',
    ),
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
