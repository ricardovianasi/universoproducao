<?php
return [
    'router' => array(
        'routes' => array(
            'universoproducao' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                )
            ),
            'cineop2017' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/cineop',
                )
            ),
        )
    )
];