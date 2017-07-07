<?php
return [
    'router' => array(
        'routes' => array(
            'cinebh2017' => array(
                'options' => array(
                    'route'    => '[:locale.]cinebh',
                    'defaults' => array(
                        'locale' => 'pt',
                    ),
                )
            ),
        )
    ),
];