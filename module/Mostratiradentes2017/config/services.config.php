<?php
use Application\Navigation;

return [
    'factories' => [
        'MostratiradentesNavigation2017' => function($e) {
            $navigation = new Navigation\SiteNavigation(10, 'mostratiradentes2017/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];