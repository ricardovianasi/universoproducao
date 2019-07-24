<?php
use Application\Navigation;

return [
    'factories' => [
        'CinebhNavigation2019' => function($e) {
            $request = $e->get('Request');
            $router = $e->get('Router');

            $locale = $router->match($request)->getParam('locale');

            $navigation = new Navigation\SiteNavigation(17, 'cinebh2019/default', $locale);
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];