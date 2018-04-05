<?php
use Application\Navigation;

return [
    'factories' => [
        'CinebhNavigation' => function($e) {
            $request = $e->get('Request');
            $router = $e->get('Router');

            $locale = $router->match($request)->getParam('locale');

            $navigation = new Navigation\SiteNavigation(13, 'cinebh/default', $locale);
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];