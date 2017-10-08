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
                'data-required' => 'required',
                'id' => 'dependent_name'

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
                'id' => 'dependent_email'
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
                'id' => 'dependent_gender'
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
                'data-required' => 'required',
                'data-inputmask' => "'alias': 'dd/mm/yyyy', 'placeholder':'_'",
                'id' => 'dependent_birth_date'
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
                'id' => 'dependent_identifier'
            ]
        ]);
	}
}