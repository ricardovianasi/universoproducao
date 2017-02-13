<?php
use Application\Navigation;

return [
    'factories' => [
        'MostratiradentesSpNavigation' => function($e) {
            $navigation = new Navigation\SiteNavigation(6, 'mostratiradentessp/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];