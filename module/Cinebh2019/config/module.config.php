<?php
namespace Cinebh2019;

use Zend\Mvc\Router\Http\Hostname;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Regex;
use Zend\Mvc\Router\Http\Segment;

return array(
    'router' => array(
        'router_class' => 'Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack',
        'routes' => array(
            'cinebh2019' => array(
                'type'    => Hostname::class,
                'type' => 'Hostname',
                'options' => array(
                    'route'    => '[www.][:locale.]cinebh.com.br',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'hotsite',
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
                                'action'        => 'hotsite',
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
                    'workshop' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/programacao/oficina/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => array(
                                'controller' => Controller\WorkshopController::class,
                                'action' => 'details',
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
                                'controller' => Controller\MovieController::class,
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
                                'controller' => Controller\SeminarController::class,
                                'action' => 'details',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'art' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/programacao/arte/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => array(
                                'controller' => Controller\ArtController::class,
                                'action' => 'details',
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
            'cinebh2019/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => [
        __NAMESPACE__ => 'cinebh2019/layout'
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
