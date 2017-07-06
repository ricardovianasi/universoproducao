<?php
use Application\Navigation;

return [
    'factories' => [
        'Cinebh2017Navigation' => function($e) {
            $request = $e->get('Request');
            $router = $e->get('Router');

            $locale = $router->match($request)->getParam('locale');

            $navigation = new Navigation\SiteNavigation(8, 'cinebh2017/default', $locale);
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];