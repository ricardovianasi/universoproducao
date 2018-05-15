<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/09/2017
 * Time: 08:54
 */

namespace Admin\Form\Registration;


use Application\Entity\Movie\Movie;
use Application\Entity\Registration\Options;

class MovieRegistrationForm extends RegistrationForm
{
    public function __construct($em = null)
    {
        parent::__construct($em);

        $this->add([
            'type' => 'select',
            'name' => 'options['.Options::MOVIE_TYPE.']',
            'options' => [
                'label' => 'Tipo do filme',
                'value_options' => Movie::getMovieTypes(),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-2',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options['.Options::MOVIE_ALLOW_INTERNATIONAL_REGISTRATION.']',
            'options' => [
                'label' => 'Permitir cadastro de filme internacional',
                'value_options' => [
                    '1' => 'Não',
                    '2' => 'Sim',
                ],
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-2',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'name' => 'options['.Options::MOVIE_ALLOW_FINISHED_FROM.']',
            'type' => 'TwbBundle\Form\Element\DatePicker',
        ]);

        $this->add([
            'name' => 'options['.Options::MOVIE_ALLOW_FINISHED_TO.']',
            'type' => 'TwbBundle\Form\Element\DatePicker',
        ]);

        $this->add([
            'name' => 'options['.Options::MOVIE_ALLOW_EDIT_REGISTRATION_TO.']',
            'options' => [
                'label' => 'Permitir edição do cadastro de filme até',
                'help-block' => 'Se não for permitido a edição do filme, o campo deverá permancer em branco'
            ]
        ]);

        $this->add([
            'name' => 'options['.Options::MOVIE_DURATION_CURTA_FROM.']',
            'options' => [
                ''
            ],
            'attributes' => [
                'data-inputmask' => "'alias': 'hh:mm:ss'"
            ]
        ]);
        $this->add([
            'name' => 'options['.Options::MOVIE_DURATION_CURTA_TO.']',
            'options' => [
                ''
            ],
            'attributes' => [
                'data-inputmask' => "'alias': 'hh:mm:ss'"
            ]
        ]);

        $this->add([
            'name' => 'options['.Options::MOVIE_DURATION_MEDIA_FROM.']',
            'options' => [
            ],
            'attributes' => [
                'data-inputmask' => "'alias': 'hh:mm:ss'"
            ]
        ]);
        $this->add([
            'name' => 'options['.Options::MOVIE_DURATION_MEDIA_TO.']',
            'options' => [
            ],
            'attributes' => [
                'data-inputmask' => "'alias': 'hh:mm:ss'"
            ]
        ]);

        $this->add([
            'name' => 'options['.Options::MOVIE_DURATION_LONGA_FROM.']',
            'options' => [
            ],
            'attributes' => [
                'data-inputmask' => "'alias': 'hh:mm:ss'"
            ]
        ]);
        $this->add([
            'name' => 'options['.Options::MOVIE_DURATION_LONGA_TO.']',
            'options' => [
            ],
            'attributes' => [
                'data-inputmask' => "'alias': 'hh:mm:ss'"
            ]
        ]);
    }

}