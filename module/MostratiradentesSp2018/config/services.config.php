<?php
use Application\Navigation;

return [
    'factories' => [
        'MostratiradentesSpNavigation2018' => function($e) {
            $navigation = new Navigation\SiteNavigation(11, 'mostratiradentessp2018/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];