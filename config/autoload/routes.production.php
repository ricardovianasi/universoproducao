<?php
return [
    'router' => array(
        'routes' => array(
            /*'universoproducao' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                )
            ),*/
            'cinebh2017' => array(
                'options' => array(
                    'route'    => '[:locale.]cinebh',
                    'defaults' => array(
                        'locale' => 'pt',
                    ),
                )
            ),
            'mostratiradentessp' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => 'mostratiradentes-sp',
                )
            ),
        )
    )
];