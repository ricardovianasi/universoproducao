<?php
namespace MeuUniverso;

use MeuUniverso\View\Helper\UserMenu;
use MeuUniverso\View\Helper\UserMovies;
use Util\Security\Crypt;

return array(
    'router' => array(
        'routes' => array(
            'meu-universo' => array(
                'type'    => 'Hostname',
                'options' => array(
                    'route'    => 'meu-universo.universoproducao.com.br',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action]',
                            'defaults' => array(
                                'controller' => Controller\IndexController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'auth' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/autenticacao[/:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\AuthController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/cadastro[/:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\RegisterController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'movie' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/filme/:id_reg/:action[/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\MovieRegistrationController::class,
                                'action' => 'index',
                            ),
                        ),
                    )
                )
            )
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'meuuniverso/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => [
        __NAMESPACE__ => 'meuuniverso/layout'
    ],
    'doctrine' => [
        'authentication_meuuniverso' => [
            'orm_default' => [
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\User\User',
                'storage' => 'DoctrineModule\Authentication\Storage\Session',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credential_callable' => function($user, $passwordGiven) {
                    return Crypt::getInstance()->testPass($passwordGiven, $user->getPassword());
                },
            ]
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'meuUniversoUserMenu' => UserMenu::class,
            'meuUniversoMovies' => UserMovies::class
        ]
        /*'factories' => [
            'meuUniversoUserMenu' => function($helpers) {
                $services = $helpers->getServiceLocator();
                return new UserMenu($services->get('meuuniverso_authenticationservice'));
            }
        ]*/
    ]
);
