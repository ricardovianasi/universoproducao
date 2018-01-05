<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 15:01
 */

namespace Admin\Form\Programing;

use TwbBundle\Form\Element\DatePicker;
use Zend\Form\Fieldset;

class ProgramingFieldset extends Fieldset
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'date',
            'type' => DatePicker::class,
            'options' => [
                'label' => 'Data',
                'placeholder' => 'Data',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'placeholder' => 'Data',
                'data-inputmask' => "'alias': 'date'",
            ]
        ]);

        $this->add([
            'name' => 'start_time',
            'options' => [
                'label' => 'Hora de início',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'data-inputmask' => "'alias': 'hh:mm:ss'",
                'placeholder' => 'Hora início'
            ],
        ]);

        $this->add([
            'name' => 'end_time',
            'options' => [
                'label' => 'Hora de término',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'data-inputmask' => "'alias': 'hh:mm:ss'",
                'placeholder' => 'Hora término'
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'place',
            'options' => [
                'label' => 'Local',
                'empty_option' => 'Selecione',
                'value_options' => [],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);
    }

}