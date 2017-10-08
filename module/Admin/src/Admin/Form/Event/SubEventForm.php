<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/10/2017
 * Time: 11:16
 */

namespace Admin\Form\Event;

use Application\Entity\Event\EventType;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class SubEventForm extends Form
{
    public function __construct()
    {
        parent::__construct('sub-event-place-form');
        $this->setAttributes([
            'class' => 'form-horizontal default-form-actions enable-validators'
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Nome da sub-mostra',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'type',
            'type' => 'select',
            'options' => [
                'label' => 'Tipo do evento',
                'empty_option' => 'Selecione',
                'value_options' => EventType::toArray(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required'
            ]

        ]);

        $this->add([
            'name' => 'description',
            'options' => [
                'label' => 'Descrição',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'id' => 'tinymce_minimal'
            ]
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'name' => [
                'name' => 'name',
                'required' => true
            ],
        ]));
    }

}