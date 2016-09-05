<?php
return [
    'factories' => [
        'authentication' => 'Admin\Auth\Factory\AuthenticationServiceFactory',
        'Admin\Auth\Authentication\DefaultAuthenticationListener' => 'Admin\Auth\Factory\DefaultAuthenticationListenerFactory',
        'Admin\Form\ExternalUser\UserForm' => function($e) {
            $em = $e->get('Doctrine\ORM\EntityManager');
            $form = new \Admin\Form\ExternalUser\UserForm($em);
            return $form;
        },
        'Admin\Form\User\UserForm' => function($e) {
            $em = $e->get('Doctrine\ORM\EntityManager');
            return new \Admin\Form\User\UserForm($em);
        },
        'Admin\Form\Page\PageForm' => function ($e) {
            $em = $e->get('Doctrine\ORM\EntityManager');
            return new \Admin\Form\Page\PageForm($em);
        },
        'Admin\Form\PostForm' => function ($e) {
            $em = $e->get('Doctrine\ORM\EntityManager');
            return new \Admin\Form\PostForm($em);
        },
        'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',

    ],
    'invokables' => [
    ],
];