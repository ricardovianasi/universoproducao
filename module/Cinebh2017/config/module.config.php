<?php
namespace Cinebh2017;

use Zend\Mvc\Router\Http\Hostname;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Regex;
use Zend\Mvc\Router\Http\Segment;

return array(
    'router' => array(
        'router_class' => 'Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack',
        'routes' => array(
            'cinebh2017' => array(
                'type'    => Hostname::class,
                'options' => array(
                    'route'    => '[:locale.]cinebh',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                        'locale'        => 'pt'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => Segment::class,
                        'options' => array(
                            'route'    => '/:slug',
                            'constraints' => array(
                                'slug' => '.+'
                            ),
                            'defaults' => array(
                                'controller' => Controller\PostController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'home' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/',
                            'defaults' => array(
                                'controller'    => Controller\IndexController::class,
                                'action'        => 'index',
                            ),
                        )
                    ),
                    'index' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/inicio',
                            'defaults' => array(
                                'controller'    => Controller\IndexController::class,
                                'action'        => 'index',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'news' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/noticia/:slug',
                            'constraints' => [
                                'slug' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => array(
                                'controller' => Controller\NewsController::class,
                                'action' => 'news',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'movie' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/programacao/filme/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => array(
                                'controller' => Controller\ProgramationController::class,
                                'action' => 'movie',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'seminar' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/programacao/seminario/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => array(
                                'controller' => Controller\ProgramationController::class,
                                'action' => 'seminar',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'newsletter' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/newsletter',
                            'defaults' => array(
                                'controller' => Controller\PostController::class,
                                'action' => 'newsletter',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'sitemap' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/sitemap.xml',
                            'defaults' => array(
                                'controller' => Controller\PostController::class,
                                'action' => 'sitemap',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'search' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/busca',
                            'defaults' => array(
                                'controller' => Controller\PostController::class,
                                'action' => 'search',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'edition' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/a-mostra/edicoes-anteriores/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => array(
                                'controller' => Controller\PreviousEditionsController::class,
                                'action' => 'edition',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'cinebh2017/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => [
        __NAMESPACE__ => 'cinebh2017/layout'
    ],

    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'base_dir' => __DIR__ . '/../languages',
                'type'     => 'gettext',
                'pattern'  => '%s.mo',
            ],
        ]
    ]
);
