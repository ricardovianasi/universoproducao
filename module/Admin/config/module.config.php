<?php
namespace Admin;

use Admin\Controller\Plugin\Service\SlugfyFactory;
use Admin\Controller\Plugin\Service\UserLogFactory;
use Admin\View\Helper\AdminNavigation;
use function foo\func;
use Zend\Mvc\Router\Http\Literal;

return array(
    'controller_plugins' => array(
        'factories' => array(
            'slugify' => SlugfyFactory::class,
            'userLog' => UserLogFactory::class
        ),
        'invokables' => array(
            'getidentity' => 'Admin\Auth\Identity\IdentityPlugin'
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => Literal::class,
                'options' => array(
                    'route'    => '/novo-admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'priority' => 9999,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'auth' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/login[/:action]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array(
                                'controller' => 'Login',
                                'action' => 'index'
                            ),
                        ),
                    ),
                    'slug' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/slug[/:site]',
                            'defaults' => array(
                                'controller'   => 'Slug',
                                'action'       => 'index'
                            ),
                        ),
                    ),
                    'page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/site/:site/page[/:action[/:id]]',
                            'constraints' => array(

                            ),
                            'defaults' => array(
                                'controller'   => 'Page',
                                'action'       => 'index'
                            ),
                        ),
                        'priority' => 999
                    ),
                    'eufacoamostra' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/site/:site/eufaco[/:action[/:id]]',
                            'constraints' => array(

                            ),
                            'defaults' => array(
                                'controller'   => 'Eufaco',
                                'action'       => 'index'
                            ),
                        ),
                        'priority' => 999
                    ),
                    'event' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/event[/:action[/:id]]',
                            'constraints' => array(

                            ),
                            'defaults' => array(
                                'controller'   => 'Event',
                                'action'       => 'index'
                            ),
                        ),
                        'priority' => 999
                    ),
                    'guide' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/site/:site/guide[/:action[/:id]]',
                            'constraints' => array(

                            ),
                            'defaults' => array(
                                'controller'   => 'Guide',
                                'action'       => 'index'
                            ),
                        ),
                        'priority' => 999
                    ),
                    'menu' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/site/:site/menu[/:action]',
                            'constraints' => [

                            ],
                            'defaults' => [
                                'controller'   => 'Menu',
                                'action'       => 'index'
                            ],
                        ],
                        'priority' => 999
                    ],
                    'banner' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/site/:site/banner[/:action[/:id]]',
                            'constraints' => [

                            ],
                            'defaults' => [
                                'controller'   => 'Banner',
                                'action'       => 'index'
                            ],
                        ],
                        'priority' => 999
                    ],
                    'gallery' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/site/:site/gallery[/:action]',
                            'constraints' => [

                            ],
                            'defaults' => [
                                'controller'   => 'Gallery',
                                'action'       => 'index'
                            ],
                        ],
                        'priority' => 999
                    ],
                    'tv' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/site/:site/tv[/:action[/:id]]',
                            'constraints' => [
                            ],
                            'defaults' => [
                                'controller'   => 'Tv',
                                'action'       => 'index'
                            ],
                        ],
                        'priority' => 999
                    ],
                    'widget' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/site/:site/widget[/:action[/:id]]',
                            'constraints' => [
                            ],
                            'defaults' => [
                                'controller'   => 'Widget',
                                'action'       => 'index'
                            ],
                        ],
                        'priority' => 999
                    ],
                    'programation' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/site/:site/programation[/:action[/:id]]',
                            'constraints' => [
                            ],
                            'defaults' => [
                                'controller'   => 'Programation',
                                'action'       => 'index'
                            ],
                        ],
                        'priority' => 999
                    ],
                    'site_add_item' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/site/add-site-item',
                            'defaults' => [
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Admin\Controller',
                                'controller'    => 'Site',
                                'action'        => 'add-site',
                            ]
                        ]
                    ]
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'not_found_template' => 'admin/error/404',
        'exception_template' => 'admin/error/index',
        'template_map' => array(
            'admin/layout'		=> __DIR__ . '/../view/layout/layout.phtml',
            'admin/footer'      => __DIR__ . '/../view/layout/_footer.phtml',
            'admin/login'		=> __DIR__ . '/../view/layout/login.phtml',
            'admin/error/404'	=> __DIR__ . '/../view/error/404.phtml',
            'admin/error/index'	=> __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'module_layouts' => array(
        __NAMESPACE__ => 'admin/layout'
    ),
    'view_helpers' => array(
        'invokables' => array(
            'adminTemplateBasePath'   	=> 'Admin\View\Helper\AdminTemplateBasePath',
            'statusLabel'             	=> 'Admin\View\Helper\StatusLabel',
            'loginDropdown'				=> 'Admin\View\Helper\LoginDropdown',
            'adminMenu'					=> 'Admin\View\Helper\AdminMenu',
            'adminMenuPages'			=> 'Admin\View\Helper\AdminMenuPages',
            'adminPostSiteView' 		=> 'Admin\View\Helper\AdminPostSiteView',
            'adminBanner'				=> 'Admin\View\Helper\AdminBanner',
            'adminGallery'				=> 'Admin\View\Helper\AdminGallery',
            'registrationStatus'        => 'Admin\View\Helper\RegistrationStatus',
            //'adminNavigation'           => AdminNavigation::class
        ),
        'factories' => [
            'adminTranslate' => function($helpers) {
                $services = $helpers->getServiceLocator();
                $app = $services->get('Application');
                $em = $services->get('Doctrine\ORM\EntityManager');
                return new View\Helper\AdminTranslate($app->getRequest(), $app->getMvcEvent(), $em);
            },
            'adminNavigation' => function($helpers) {
                $services = $helpers->getServiceLocator();
                $auth = $services->get('authentication');
                return new AdminNavigation($auth);

            }
        ]
    ),

    'template_base_path' => array(
        'admin' => 'assets/admin/template/metronic_v4.5.5/theme'
    )
);