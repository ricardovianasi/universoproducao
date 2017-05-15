<?php
return [
    'router' => array(
        'routes' => array(
            'cineop2017' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/cineop2017',
                ),
                'priority' => 1000000
            ),
            'universoproducao' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                ),
                'priority' => 100000
            ),
        )
    )
];