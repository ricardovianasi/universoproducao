<?php
use Application\Navigation;

return [
    'factories' => [
        'Cineop2018Navigation' => function($e) {
            $navigation = new Navigation\SiteNavigation(12, 'cineop2018/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];