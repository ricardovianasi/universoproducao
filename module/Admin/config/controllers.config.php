<?php
namespace Admin;

use Admin\Controller;

return [
    'invokables' => [
        /*'Admin\Controller\Index'     		=> Controller\IndexController::class,
        'Admin\Controller\Page'      		=> Controller\PageController::class,
        'Admin\Controller\Login'     		=> Controller\LoginController::class,
        'Admin\Controller\Slug'      	  	=> Controller\SlugController::class,
        'Admin\Controller\FileManager'    	=> Controller\FileManagerController::class,
        'Admin\Controller\Account'    		=> Controller\AccountController::class,
        'Admin\Controller\ExternalUser'		=> Controller\ExternalUserController::class,
        'Admin\Controller\News'				=> Controller\NewsController::class,
        'Admin\Controller\Profile'			=> Controller\ProfileController::class,
        'Admin\Controller\User'				=> Controller\UserController::class,
        'Admin\Controller\Menu'				=> Controller\MenuController::class,
        'Admin\Controller\Banner'			=> Controller\BannerController::class*/
    ],
    'abstract_factories' => [
        Controller\Factory\DefaultControllerFactory::class,
        ///Controller\Factory\PostControllerFactory::class
    ],
    'factories' => [
        'Admin\Controller\Page'		=> Controller\Factory\PageControllerFactory::class,
        'Admin\Controller\News'		=> Controller\Factory\NewsControllerFactory::class,
        'Admin\Controller\Login' 	=> Controller\Factory\LoginControllerFactory::class,
        'Admin\Controller\Site' 	=> Controller\Factory\SiteControllerFactory::class,
        'Admin\Controller\Banner'	=> Controller\Factory\BannerControllerFactory::class,
        'Admin\Controller\Gallery'	=> Controller\Factory\GalleryControllerFactory::class,
        'Admin\Controller\Menu'		=> Controller\Factory\MenuControllerFactory::class,
        'Admin\Controller\Guide'    => Controller\Factory\GuideControllerFactory::class,
        'Admin\Controller\Tv'               => Controller\Factory\TvControllerFactory::class,
        'Admin\Controller\Programation'     => Controller\Factory\ProgramationControllerFactory::class,
    ]
];