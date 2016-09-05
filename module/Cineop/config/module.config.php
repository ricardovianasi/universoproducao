<?php
namespace Cineop;

use Zend\Mvc\Router\Http\Hostname;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Regex;

return array(
    'router' => array(
        'routes' => array(
            'cineop' => array(
                'type'    => Hostname::class,
                'options' => array(
                    'route'    => '[www.]cineop.com.br',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Cineop\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => Regex::class,
                        'options' => array(
                            'regex'    => '(?<slug>.+)',
                            'spec' => '%slug%',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Cineop\Controller',
                                'controller' => 'Index',
                                'action' => 'post',
                            ),
                        ),
                    ),
                    'home' => array(
                        'type' => Literal::class,
                        'options' => array(
                            'route' => '/',
                            'defaults' => array(
                            ),
                        )
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'cineop/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => array(
        __NAMESPACE__ => 'cineop/layout'
    ),
    'view_helpers' => array(
        'factories' => array(
            'cineopMenu' => function($sm) {
                $entityManager = $sm->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $items = $entityManager
                    ->getRepository('Application\Entity\Site\Menu\Menu')
                    ->findOneBy(['site'=>2]);

                $urlHelper = $sm->get('url');

                $helper = new View\Helper\Menu($items->getItems(), $urlHelper);
                return $helper;
            }
        )
    ),
);
