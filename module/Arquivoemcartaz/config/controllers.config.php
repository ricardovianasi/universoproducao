<?php
namespace Arquivoemcartaz;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'factories' => [
        Controller\IndexController::class => InvokableFactory::class,
        Controller\NewsController::class => InvokableFactory::class,
        Controller\PostController::class => InvokableFactory::class,
    ]
];