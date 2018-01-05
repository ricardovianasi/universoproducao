<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 04/01/2018
 * Time: 09:48
 */

namespace Admin\Form\Movie;

use Application\Entity\Programing\Meta;
use Zend\Form\Fieldset;

class MetaProgramingFieldset extends Fieldset
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => Meta::SESSION_TITLE,
            'options' => [
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Nome da sessão'
            ]
        ]);

        $this->add([
            'name' => Meta::ADDITIONAL_INFO,
            'type' => 'Textarea',
            'options' => [
                'label' => 'Informações adicionais',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'name' => Meta::NATIONAL_PREMIERE,
            'type' => 'Select',
            'options' => [
                'label' => 'Pré-estreia nacional',
                'empty_option' => 'Selecione',
                'value_options' => [
                    '1' => 'Não',
                    '2' => 'Sim',
                ],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'name' => Meta::WORLD_PREMIERE,
            'type' => 'Select',
            'options' => [
                'label' => 'Pré-estreia mundial',
                'empty_option' => 'Selecione',
                'value_options' => [
                    '1' => 'Não',
                    '2' => 'Sim',
                ],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            Meta::WORLD_PREMIERE => [
                'name' => Meta::WORLD_PREMIERE,
                'required'   => false,
                'allow_empty' => true
            ],
            Meta::NATIONAL_PREMIERE => [
                'name' => Meta::NATIONAL_PREMIERE,
                'required'   => false,
                'allow_empty' => true
            ],
            Meta::ADDITIONAL_INFO => [
                'name' => Meta::ADDITIONAL_INFO,
                'required'   => false,
                'allow_empty' => true
            ],
        ];
    }
}