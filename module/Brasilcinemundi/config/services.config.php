<?php
use Application\Navigation;

return [
    'factories' => [
        'Brasilcinemundi' => function($e) {
            $request = $e->get('Request');
            $router = $e->get('Router');

            $locale = $router->match($request)->getParam('locale');

            $navigation = new Navigation\SiteNavigation(18, 'brasilcinemundi/default', $locale);
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];