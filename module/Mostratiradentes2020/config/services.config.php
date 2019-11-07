<?php
use Application\Navigation;

return [
    'factories' => [
        'MostratiradentesNavigation2020' => function($e) {
            $navigation = new Navigation\SiteNavigation(19, 'mostratiradentes2020/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];