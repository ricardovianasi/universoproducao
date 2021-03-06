<?php
namespace MeuUniverso;

use MeuUniverso\View\Helper\LabelStatus;
use MeuUniverso\View\Helper\RegistrationRegulation;
use MeuUniverso\View\Helper\UserEducationalProjects;
use MeuUniverso\View\Helper\UserMenu;
use MeuUniverso\View\Helper\UserMovies;
use MeuUniverso\View\Helper\UserProjects;
use MeuUniverso\View\Helper\UserSeminar;
use MeuUniverso\View\Helper\UserSessionSchool;
use MeuUniverso\View\Helper\UserWorkshops;
use Util\Security\Crypt;
use Util\View\Helper\Messages;

return array(
    'controller_plugins' => array(
        'factories' => array(
            'meuUniversoMessages' => function() {
                $sessionContainer = new \Zend\Session\Container('meu_universo_messages');
                return new \Util\Controller\Plugin\Messages($sessionContainer);
            },
        ),
    ),
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

                    'dependents' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/dependentes[/:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\DependentsController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'movie_view' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/filme/visualizar/:id',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\MovieRegistrationController::class,
                                'action' => 'visualizar',
                            ),
                        ),
                        'priority' => '100'
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
                    ),
                    'moving_city_movie' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/filme-cidade-em-movimento/:id_reg/:action[/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\MovieMovingCityRegistrationController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'moving_city_movie_view' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/filme-cidade-em-movimento/visualizar/:id',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\MovieMovingCityRegistrationController::class,
                                'action' => 'visualizar',
                            ),
                        ),
                        'priority' => '100'
                    ),
                    'educational_movie' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/filme-mostra-educacao/:id_reg/:action[/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\EducationalMovieRegistrationController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'educational_movie_view' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/filme-mostra-educacao/visualizar/:id',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\EducationalMovieRegistrationController::class,
                                'action' => 'visualizar',
                            ),
                        ),
                        'priority' => '100'
                    ),
                    'workshop' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/oficina/:id_reg/[:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\WorkshopRegistrationController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'session_school' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/sessao-cine-escola/:id_reg/[:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\SessionSchoolController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'seminar' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/seminar/:id_reg/[:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\SeminarController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'project' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/projeto/:id_reg/:action[/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\ProjectRegistrationController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'educational_project' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/projeto-educational/:id_reg/:action[/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\EducationalProjectController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'workshop_proposal' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/proposta-oficina[/:action]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\WorkshopProposalController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'artistic_proposal' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/proposta-artistica[/:action]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => Controller\ArtisticProposalController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
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
            'meuUniversoUserMenu'               => UserMenu::class,
            'meuUniversoMovies'                 => UserMovies::class,
            'meuUniversoProjects'               => UserProjects::class,
            'meuUniversoEducationalProjects'    => UserEducationalProjects::class,
            'meuUniversoWorkshops'              => UserWorkshops::class,
            'meuUniversoSessionSchool'          => UserSessionSchool::class,
            'meuUniversoSeminars'               => UserSeminar::class,
            'regulation'                        => RegistrationRegulation::class,
            'labelStatus'                       => LabelStatus::class
        ],
        'factories' => [
            'meuUniversoMessages' => function($e) {
                $sm = $e->getServiceLocator();
                $messagesPlugin = $sm->get('ControllerPluginManager')->get('meuUniversoMessages');
                $messages = $messagesPlugin->getMergedMessages();
                $helper = new \Util\View\Helper\Messages($messages);
                return $helper;
            }
        ]
    ],
);
