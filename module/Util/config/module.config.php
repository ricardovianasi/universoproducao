<?php
return [
	'controllers' => [
		'invokables' => [
			'Util\Controller\Adderess' => 'Util\Controller\AdderessController',
			'Util\Controller\Password' => 'Util\Controller\PasswordController',
			'Util\Controller\Post'		=> 'Util\Controller\PostController',
			'Util\Controller\Event'		=> 'Util\Controller\EventController',
		]
	],
	'controller_plugins' => [
		'invokables' => [
			'messages' => 'Util\Controller\Plugin\Messages'
		],
	],
	'view_helpers' => [
		'invokables' => [
			'cpf'	=> 'Util\View\Helper\Cpf',
			'cpf'	=> 'Util\View\Helper\Cnpj',
			'note'	=> 'Util\View\Helper\Note'
		],
		'factories' => [
			'messages' => function($e) {
				$sm = $e->getServiceLocator();
				$messagesPlugin = $sm->get('ControllerPluginManager')->get('messages');
				$messages = $messagesPlugin->getMergedMessages();
				$helper = new \Util\View\Helper\Messages($messages);
				return $helper;
			},
			'Util\View\Helper\FormCep' => function($e) {
				$helper = new \Util\View\Helper\FormCep($e);
				return $helper;
			}
		]
	],

	'router' => [
		'routes' => [
		    'util' => [
		        'type' => 'Literal',
                'options' => [
                    'route' => '/s',
                    'defaults' => [
                        '__NAMESPACE__' => 'Util\Controller'
                    ],
                ],
                'may_terminate' => true,
                'priority' => 9999,
                'child_routes' => [
                    'cep' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route' => '/cep',
                            'defaults' => [
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Util\Controller',
                                'controller'    => 'Adderess',
                                'action'        => 'cep',
                            ]
                        ]
                    ],
                    'cities' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route' => '/cities',
                            'defaults' => [
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Util\Controller',
                                'controller'    => 'Adderess',
                                'action'        => 'cities',
                            ]
                        ]
                    ],
                    'event' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route' => '/event',
                            'defaults' => [
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Util\Controller',
                                'controller'    => 'Event',
                                'action'        => 'event',
                            ]
                        ]
                    ],
                    'password' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route' => '/generate-password',
                            'defaults' => [
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Util\Controller',
                                'controller'    => 'Password',
                                'action'        => 'generate-password',
                            ]
                        ]
                    ],
                    'site_item' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/post/add-site-item',
                            'defaults' => [
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Util\Controller',
                                'controller'    => 'Post',
                                'action'        => 'add-site',
                            ]
                        ]
                    ]
                ]
            ],
		]
	]
];