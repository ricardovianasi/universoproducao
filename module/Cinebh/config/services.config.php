<?php
use Application\Navigation;

return [
    'factories' => [
        'CinebhNavigation' => function($e) {
            $navigation = new Navigation\SiteNavigation(4, 'cinebh/default');
            return $navigation->createService($e);
        }
    ]
];