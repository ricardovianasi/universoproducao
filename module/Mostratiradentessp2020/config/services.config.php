<?php
use Application\Navigation;

return [
    'factories' => [
        'MostratiradentesspNavigation2020' => function($e) {
            $navigation = new Navigation\SiteNavigation(20, 'mostratiradentessp2020/default');
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];