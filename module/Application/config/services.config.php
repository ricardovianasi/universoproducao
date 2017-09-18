<?php
namespace Application;

return [
    'factories' => [
        'SiteNavigation' => Navigation\SiteNavigationFactory::class,
        'UniversoproducaoNavigation' => function($e) {
            $navigation = new Navigation\SiteNavigation(1, 'universoproducao/default');
            return $navigation->createService($e);
        }
    ],
    'invokables' => [
        'mailService' => Service\MailService::class
    ]
];
