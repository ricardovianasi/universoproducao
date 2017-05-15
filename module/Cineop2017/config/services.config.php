<?php
use Application\Navigation;

return [
    'factories' => [
        'Cineop2017Navigation' => function($e) {
            $navigation = new Navigation\SiteNavigation(7, 'cineop2017/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];