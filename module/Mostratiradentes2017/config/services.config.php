<?php
use Application\Navigation;

return [
    'factories' => [
        'MostratiradentesNavigation' => function($e) {
            $navigation = new Navigation\SiteNavigation(10, 'mostratiradentes/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];