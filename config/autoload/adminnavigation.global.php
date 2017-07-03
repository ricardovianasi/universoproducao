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
				'label' => 'Gerenciador de Arquivos',
				'route' => 'admin/default',
				'controller' => 'file-manager',
				'icon' => 'icon-picture',
			],
			[
				'label' => 'Usuários',
				'route' => 'admin/default',
				'controller'=>'user',
				'icon' => 'icon-users'
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
                    ],
                    [
                        'label' => 'Widgets',
                        'route' => 'admin/widget',
                        'icon' => 'icon-book-open',
                        'params' => [
                            'site' => 7
                        ]
                    ],
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
                            'site' => 4
                        ]
                    ],
                    [
                        'label' => 'Guia',
                        'route' => 'admin/guide',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 4
                        ]
                    ],
                    [
                        'label' => 'Menu',
                        'route' => 'admin/menu',
                        'icon' => 'icon-list',
                        'params' => [
                            'site' => 4
                        ]
                    ],
                    [
                        'label' => 'Banner',
                        'route' => 'admin/banner',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 4
                        ]
                    ],
                    [
                        'label' => 'Galeria',
                        'route' => 'admin/gallery',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 4
                        ]
                    ],
                    [
                        'label' => 'TV',
                        'route' => 'admin/tv',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 4
                        ]
                    ],
                    [
                        'label' => 'Programação',
                        'route' => 'admin/programation',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 4
                        ]
                    ],
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
                            'site' => 3
                        ]
                    ],
                    [
                        'label' => 'Guia',
                        'route' => 'admin/guide',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 3
                        ]
                    ],
                    [
                        'label' => 'Menu',
                        'route' => 'admin/menu',
                        'icon' => 'icon-list',
                        'params' => [
                            'site' => 3
                        ]
                    ],
                    [
                        'label' => 'Banner',
                        'route' => 'admin/banner',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 3
                        ]
                    ],
                    [
                        'label' => 'Galeria',
                        'route' => 'admin/gallery',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 3
                        ]
                    ],
                    [
                        'label' => 'TV',
                        'route' => 'admin/tv',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 3
                        ]
                    ],
                    [
                        'label' => 'Programação',
                        'route' => 'admin/programation',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 3
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
                            'site' => 5
                        ]
                    ],
                    [
                        'label' => 'Páginas',
                        'route' => 'admin/page',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 5
                        ]
                    ],
                    [
                        'label' => 'Guia',
                        'route' => 'admin/guide',
                        'icon' => 'icon-doc',
                        'params' => [
                            'site' => 5
                        ]
                    ],
                    [
                        'label' => 'Menu',
                        'route' => 'admin/menu',
                        'icon' => 'icon-list',
                        'params' => [
                            'site' => 5
                        ]
                    ],
                    [
                        'label' => 'Banner',
                        'route' => 'admin/banner',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 5
                        ]
                    ],
                    [
                        'label' => 'Galeria',
                        'route' => 'admin/gallery',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 5
                        ]
                    ],
                    [
                        'label' => 'TV',
                        'route' => 'admin/tv',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 5
                        ]
                    ],
                    [
                        'label' => 'Programação',
                        'route' => 'admin/programation',
                        'icon' => 'fa fa-image',
                        'params' => [
                            'site' => 5
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
				'label' => 'Mostras',
				'route' => 'admin/event',
				'controller' => 'event',
				'icon' => 'icon-book-open'
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