<?php
use Application\Navigation;

return [
    'factories' => [
        'MostratiradentesNavigationSp' => function($e) {
            $navigation = new Navigation\SiteNavigation(15, 'mostratiradentessp/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];