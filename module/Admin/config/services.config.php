<?php
namespace Admin;

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
            return new Form\User\UserForm($em);
        },
        'Admin\Form\Page\PageForm' => function ($e) {
            $em = $e->get('Doctrine\ORM\EntityManager');
            return new Form\Page\PageForm($em);
        },
        'Admin\Form\PostForm' => function ($e) {
            $em = $e->get('Doctrine\ORM\EntityManager');
            return new Form\PostForm($em);
        },
        'Admin\Form\News\NewsForm' => function ($e) {
            $em = $e->get('Doctrine\ORM\EntityManager');
            return new Form\News\NewsForm($em);
        },
        'Admin\Form\Channel\VideoForm' => function ($e) {
            $em = $e->get('Doctrine\ORM\EntityManager');
            return new Form\Channel\VideoForm($em);
        },
        'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',

    ],
    'invokables' => [
    ],
];