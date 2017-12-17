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
		    'name' => 'name',
            'options' => [
                'label' => 'Nome',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'dependent_name'

            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'gender',
            'options' => [
                'label' => 'Sexo',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
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
            'name' => 'birth_date',
            'required' => true,
            'type' => 'TwbBundle\Form\Element\DatePicker',
            'options' => [
                'label' => 'Data de Nascimento',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'data-inputmask' => "'alias': 'dd/mm/yyyy', 'placeholder':'_'",
                'id' => 'dependent_birth_date'
            ]
        ]);

        $this->add([
            'name' => 'identifier',
            'options' => [
                'label' => 'Documento',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'id' => 'dependent_identifier',
                'placeholder' => 'CPF ou identidade'
            ]
        ]);
	}
}