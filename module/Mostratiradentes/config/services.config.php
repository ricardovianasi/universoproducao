<?php
use Application\Navigation;

return [
    'factories' => [
        'MostratiradentesNavigation' => function($e) {
            $request = $e->get('Request');
            $router = $e->get('Router');

            $locale = $router->match($request)->getParam('locale');

            $navigation = new Navigation\SiteNavigation(5, 'mostratiradentes/default', $locale);
            return $navigation->createService($e);
        },
    ],
    'aliases' => [
        'translator' => 'mvctranslator',
    ],
];