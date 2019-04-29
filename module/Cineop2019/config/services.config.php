<?php
use Application\Navigation;

return [
    'factories' => [
        'Cineop2019Navigation' => function($e) {
            $navigation = new Navigation\SiteNavigation(16, 'cineop2019/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];