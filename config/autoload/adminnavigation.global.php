<?php
return [
	'navigation' => [
		'default' => [
			[
				'label' => 'Dashboard',
				'route' => 'admin/default',
				'controller' => 'index',
				'icon' => 'icon-home',
				'class' => 'start'
			],
			[
				'label' => 'Usuários',
				'route' => 'admin/default',
				'controller'=>'user',
				'icon' => 'icon-users'
			],
            [
                'label' => 'Inscrições',
                'route' => 'admin/default',
                'controller'=>'registration',
                'icon' => 'icon-picture'
            ],
            [
                'label' => 'Filmes',
                'uri' => '#',
                'icon' => 'icon-book-open',
                'pages' => [
                    [
                        'label' => 'Configurações',
                        'route' => 'admin/default',
                        'controller' => 'movie-options',
                        'icon' => 'icon-doc',

                    ],
                    [
                        'label' => 'Filmes',
                        'route' => 'admin/default',
                        'controller' => 'movie',
                        'icon' => 'icon-doc',
                    ],
                ]
            ],
            [
                'label' => 'Oficinas',
                'uri' => '#',
                'icon' => 'icon-book-open',
                'pages' => [
                    [
                        'label' => 'Responsáveis',
                        'route' => 'admin/default',
                        'controller' => 'workshop-manager',
                        'icon' => 'icon-doc',

                    ],
                    [
                        'label' => 'Oficinas',
                        'route' => 'admin/default',
                        'controller' => 'workshop',
                        'icon' => 'icon-doc',
                    ],
                    [
                        'label' => 'Programação',
                        'route' => 'admin/default',
                        'controller' => 'workshop-programing',
                        'icon' => 'icon-doc',
                    ],
                    [
                        'label' => 'Inscrições',
                        'route' => 'admin/default',
                        'controller' => 'workshop-registrations',
                        'icon' => 'icon-doc',
                    ],
                ]
            ],
			[
				'label' => 'Notícias',
				'route' => 'admin/default',
				'controller' => 'news',
				'icon' => 'icon-book-open'
			],
            [
                'label' => 'Canal Universo',
                'uri' => '#',
                'icon' => 'icon-book-open',
                'pages' => [
                    [
                        'label' => 'Videos',
                        'route' => 'admin/default',
                        'controller' => 'channel',
                        'icon' => 'icon-doc',

                    ],
                    [
                        'label' => 'Categorias',
                        'route' => 'admin/default',
                        'controller' => 'channel-category',
                        'icon' => 'icon-doc',
                    ],
                ]
            ],
			[
				'label' => 'Portais',
				'uri' => '#',
				'heading' => true
			],
			[
				'label' => 'Universo Produção',
				'uri' => '#',
				'icon' => ' icon-globe',
				'pages' => [
					[
						'label' => 'Páginas',
						'route' => 'admin/page',
						'icon' => 'icon-doc',
						'params' => [
							'site' => 1
						]
					],
					[
						'label' => 'Menu',
						'route' => 'admin/menu',
						'icon' => 'icon-list',
						'params' => [
							'site' => 1
						]
					],
					[
						'label' => 'Banner',
						'route' => 'admin/banner',
						'icon' => 'fa fa-image',
						'params' => [
							'site' => 1
						]
					],
                    [
                        'label' => 'Galeria',
                        'route' => 'admin/gallery',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 1
                        ]
                    ],
                    [
                        'label' => 'Widgets',
                        'route' => 'admin/widget',
                        'icon' => 'icon-book-open',
                        'params' => [
                            'site' => 1
                        ]
                    ],
				]
			],
			[
				'label' => 'CineOP',
				'uri' => '#',
				'icon' => ' icon-globe',
				'pages' => [
					[
						'label' => 'Páginas',
						'route' => 'admin/page',
						'icon' => 'icon-doc',
						'params' => [
							'site' => 7
						]
					],
					[
						'label' => 'Guia',
						'route' => 'admin/guide',
						'icon' => 'icon-doc',
						'params' => [
							'site' => 7
						]
					],
					[
						'label' => 'Menu',
						'route' => 'admin/menu',
						'icon' => 'icon-list',
						'params' => [
							'site' => 7
						]
					],
					[
						'label' => 'Banner',
						'route' => 'admin/banner',
						'icon' => 'fa fa-image',
						'params' => [
							'site' => 7
						]
					],
					[
						'label' => 'Galeria',
						'route' => 'admin/gallery',
						'icon' => 'fa fa-image',
						'params' => [
							'site' => 7
						]
					],
					[
						'label' => 'TV',
						'route' => 'admin/tv',
						'icon' => 'fa fa-image',
						'params' => [
							'site' => 7
						]
					],
					[
						'label' => 'Programação',
						'route' => 'admin/programation',
						'icon' => 'fa fa-image',
						'params' => [
							'site' => 7
						]
					],
                    [
                        'label' => 'Eu Faço a Mostra',
                        'route' => 'admin/eufacoamostra',
                        'icon' => 'icon-book-open',
                        'params' => [
                            'site' => 7
                        ]
                    ]
				]
			],
            [
                'label' => 'CineBH',
                'uri' => '#',
                'icon' => ' icon-globe',
                'pages' => [
                    [
                        'label' => 'Páginas',
                        'route' => 'admin/page',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 8
                        ]
                    ],
                    [
                        'label' => 'Guia',
                        'route' => 'admin/guide',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 8
                        ]
                    ],
                    [
                        'label' => 'Menu',
                        'route' => 'admin/menu',
                        'icon' => 'icon-list',
                        'params' => [
                            'site' => 8
                        ]
                    ],
                    [
                        'label' => 'Banner',
                        'route' => 'admin/banner',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 8
                        ]
                    ],
                    [
                        'label' => 'Galeria',
                        'route' => 'admin/gallery',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 8
                        ]
                    ],
                    [
                        'label' => 'TV',
                        'route' => 'admin/tv',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 8
                        ]
                    ],
                    [
                        'label' => 'Programação',
                        'route' => 'admin/programation',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 8
                        ]
                    ],
                    [
                        'label' => 'Eu Faço a Mostra',
                        'route' => 'admin/eufacoamostra',
                        'icon' => 'icon-book-open',
                        'params' => [
                            'site' => 8
                        ]
                    ]
                ]
            ],
            [
                'label' => 'Arquivo em Cartaz',
                'uri' => '#',
                'icon' => ' icon-globe',
                'pages' => [
                    [
                        'label' => 'Páginas',
                        'route' => 'admin/page',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 9
                        ]
                    ],
                    [
                        'label' => 'Guia',
                        'route' => 'admin/guide',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 9
                        ]
                    ],
                    [
                        'label' => 'Menu',
                        'route' => 'admin/menu',
                        'icon' => 'icon-list',
                        'params' => [
                            'site' => 9
                        ]
                    ],
                    [
                        'label' => 'Banner',
                        'route' => 'admin/banner',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 9
                        ]
                    ],
                    [
                        'label' => 'Galeria',
                        'route' => 'admin/gallery',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 9
                        ]
                    ],
                    [
                        'label' => 'TV',
                        'route' => 'admin/tv',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 9
                        ]
                    ],
                    [
                        'label' => 'Programação',
                        'route' => 'admin/programation',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 9
                        ]
                    ],
                ]
            ],
            [
                'label' => 'Mostra Tiradentes',
                'uri' => '#',
                'icon' => ' icon-globe',
                'pages' => [
                    [
                        'label' => 'Eu Faço a Mostra',
                        'route' => 'admin/eufacoamostra',
                        'icon' => 'icon-book-open',
                        'params' => [
                            'site' => 10
                        ]
                    ],
                    [
                        'label' => 'Páginas',
                        'route' => 'admin/page',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 10
                        ]
                    ],
                    [
                        'label' => 'Guia',
                        'route' => 'admin/guide',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 10
                        ]
                    ],
                    [
                        'label' => 'Menu',
                        'route' => 'admin/menu',
                        'icon' => 'icon-list',
                        'params' => [
                            'site' => 10
                        ]
                    ],
                    [
                        'label' => 'Banner',
                        'route' => 'admin/banner',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 10
                        ]
                    ],
                    [
                        'label' => 'Galeria',
                        'route' => 'admin/gallery',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 10
                        ]
                    ],
                    [
                        'label' => 'TV',
                        'route' => 'admin/tv',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 10
                        ]
                    ],
                    [
                        'label' => 'Programação',
                        'route' => 'admin/programation',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 10
                        ]
                    ],
                ]
            ],
            [
                'label' => 'Mostra Tiradentes SP',
                'uri' => '#',
                'icon' => ' icon-globe',
                'pages' => [
                    [
                        'label' => 'Eu Faço a Mostra',
                        'route' => 'admin/eufacoamostra',
                        'icon' => 'icon-book-open',
                        'params' => [
                            'site' => 6
                        ]
                    ],
                    [
                        'label' => 'Páginas',
                        'route' => 'admin/page',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 6
                        ]
                    ],
                    [
                        'label' => 'Guia',
                        'route' => 'admin/guide',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 6
                        ]
                    ],
                    [
                        'label' => 'Menu',
                        'route' => 'admin/menu',
                        'icon' => 'icon-list',
                        'params' => [
                            'site' => 6
                        ]
                    ],
                    [
                        'label' => 'Banner',
                        'route' => 'admin/banner',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 6
                        ]
                    ],
                    [
                        'label' => 'Galeria',
                        'route' => 'admin/gallery',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 6
                        ]
                    ],
                    [
                        'label' => 'TV',
                        'route' => 'admin/tv',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 6
                        ]
                    ],
                    [
                        'label' => 'Programação',
                        'route' => 'admin/programation',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 6
                        ]
                    ],
                ]
            ],
			[
				'label' => 'Configurações',
				'uri' => '#',
				'heading' => true
			],
			[
				'label' => 'Eventos',
                'uri' => '#',
				'icon' => 'icon-book-open',
                'pages' => [
                    [
                        'label' => 'Mostras',
                        'route' => 'admin/event',
                        'controller' => 'event',
                        'icon' => 'icon-eye'
                    ],
                    [
                        'label' => 'Locais',
                        'route' => 'admin/default',
                        'controller' => 'event-place',
                        'icon' => 'icon-eye'
                    ],
                    [
                        'label' => 'Sub-mostras',
                        'route' => 'admin/default',
                        'controller' => 'event-sub',
                        'icon' => 'icon-eye'
                    ],
                ]
			],
            [
                'label' => 'Comunicados',
                'route' => 'admin/default',
                'controller' => 'notify',
                'icon' => 'icon-envelope-open',
            ],
			[
				'label' => 'Controle de Acesso',
				'uri' => '#',
				'icon' => 'icon-lock',
				'pages' => [
					[
						'label' => 'Perfis',
						'route' => 'admin/default',
						'controller' => 'profile',
						'icon' => 'icon-eye'
					],
					[
						'label' => 'Usuários',
						'route' => 'admin/default',
						'controller' => 'admin-user',
						'icon' => 'icon-users'
					],
				]
			],
		]
	],
];