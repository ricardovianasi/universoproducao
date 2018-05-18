<?php
namespace Cineop2018;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'factories' => [
        Controller\IndexController::class               => InvokableFactory::class,
        Controller\NewsController::class                => InvokableFactory::class,
        Controller\PostController::class                => InvokableFactory::class,
        Controller\ProgramationController::class        => InvokableFactory::class,
        Controller\PreviousEditionsController::class    => InvokableFactory::class,
        Controller\WorkshopController::class            => InvokableFactory::class,
        Controller\ArtController::class                 => InvokableFactory::class,
        Controller\MovieController::class               => InvokableFactory::class,
        Controller\SeminarController::class             => InvokableFactory::class,
    ]
];