<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 02/02/2016
 * Time: 09:57
 */

namespace Admin\Form\ExternalUser;

use Application\Entity\Phone\PhoneType;
use Zend\Form\Form;

class DependentForm extends Form
{
	public function __construct()
	{
	    parent::__construct('dependent');

		$this->add([
		    'name' => 'dependent[name]',
            'options' => [
                'label' => 'Nome',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
                'data-required' => 'required'

            ]
        ]);

        $this->add([
            'name' => 'dependent[email]',
            'options' => [
                'label' => 'Email',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'dependent[gender]',
            'options' => [
                'label' => 'Sexo',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
                'empty_option' => 'Selecione',
                'value_options' => [
                    'm' => 'Masculino',
                    'f' => 'Feminino'
                ]
            ],
            'attributes' => [
                'data-required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'dependent[birth_date]',
            'required' => true,
            'type' => 'TwbBundle\Form\Element\DatePicker',
            'options' => [
                'label' => 'Data de Nascimento',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
            ],
            'attributes' => [
                'data-required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'dependent[identifier]',
            'options' => [
                'label' => 'Documento',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);
	}
}