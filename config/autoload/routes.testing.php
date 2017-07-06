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
            'cinebh2017' => array(
                'options' => array(
                    'route'    => '[:locale.]cinebh2017',
                    'defaults' => array(
                        'locale' => 'pt',
                    ),
                )
            ),
        )
    )
];