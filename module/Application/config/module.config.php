<?php
namespace Application;

use Util\Security\Crypt;
use Zend\Mvc\Router\Http\Hostname;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return array(
    'router' => array(
        'routes' => array(
            'universoproducao' => array(
                'type'    => Hostname::class,
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => Segment::class,
                        'options' => array(
                            'route'    => ':slug',
                            'constraints' => array(
                                'slug' => '.+'
                            ),
                            'defaults' => array(
                                'controller' => Controller\PostController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'news' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => 'noticia/:slug',
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
                    'channel' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => 'canal-universo/video/:id',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]*'
                            ],
                            'defaults' => array(
                                'controller' => Controller\ChannelController::class,
                                'action' => 'video',
                            ),
                        ),
                        'priority' => '999999'
                    ),
                    'channel_categories' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => 'canal-universo/:slug',
                            'constraints' => [
                                'slug' => '[a-zA-Z0-9_-]*'
                            ],
                            'defaults' => array(
                                'controller' => Controller\ChannelController::class,
                                'action' => 'category',
                            ),
                        ),
                        'priority' => '999999'
                    ),
                    'newsletter' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => 'newsletter',
                            'defaults' => array(
                                'controller' => Controller\PostController::class,
                                'action' => 'newsletter',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'search' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => 'busca',
                            'defaults' => array(
                                'controller' => Controller\PostController::class,
                                'action' => 'search',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                    'sitemap' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => 'sitemap.xml',
                            'defaults' => array(
                                'controller' => Controller\PostController::class,
                                'action' => 'sitemap',
                            ),
                        ),
                        'priority' => '99999'
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array (
        'invokables' => array (
            'cpf' 			=> View\Helper\Cpf::class,
            'youtubeEmbed' 	=> View\Helper\YoutubeEmbed::class,
            'truncate'      => View\Helper\Truncate::class,
            'thumborize'    => View\Helper\Thumborize::class,
            'gender'        => View\Helper\Gender::class
        ),
        'factories' => [
            'thumbor' => function($e) {
                $config = $e->getServiceLocator()->get('config');
                $thumborUrl = $config['thumbor']['url'];

                return new View\Helper\Thumbor($thumborUrl);
            },
            'shortcode' => function($e) {
                $em = $e->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                return new View\Helper\Shortcode($em);
            }
        ]
    ),
    'doctrine' => array(
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            __NAMESPACE__ . '_driver' => array (
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    realpath(__DIR__) . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),
            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
        'configuration' => array (
            'orm_default' => array (
                'datetime_functions' => array (
                    'YEAR'          => 'DoctrineExtensions\Query\Mysql\Year',
                    'MONTH'         => 'DoctrineExtensions\Query\Mysql\Month',
                    'DAY'           => 'DoctrineExtensions\Query\Mysql\Day',
                    'JSON_CONTAINS' =>  'Syslogic\DoctrineJsonFunctions\Query\AST\Functions\Mysql\JsonContains'
                )
            ),
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\AdminUser\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credential_callable' => function($user, $passwordGiven) {
                    return Crypt::getInstance()->testPass($passwordGiven, $user->getPassword());
                },
            )
        )
    )
);
