<?php
namespace Cineop2017;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'factories' => [
        Controller\IndexController::class           => InvokableFactory::class,
        Controller\NewsController::class            => InvokableFactory::class,
        Controller\PostController::class            => InvokableFactory::class,
        Controller\ProgramationController::class    => InvokableFactory::class,
        Controller\PreviousEditionsController::class    => InvokableFactory::class,
    ]
];