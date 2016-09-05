<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/03/2016
 * Time: 12:06
 */

namespace Admin\Form;


use Zend\Form\Form;

class BannerForm extends Form
{
	public function __construct()
	{
		parent::__construct();
        $this->setAttributes([
//            'class' => 'form-horizontal'
        ]);

        $this->add([
            'name' => 'file',
            'type' => 'hidden'
        ]);

		$this->add([
			'name' => 'title',
			'attributes' => [
				'placeholder' => 'Informe um título'
			],
            'options' => [
                'label' => 'Título'
            ]
		]);

        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'attributes' => [
                'rows' => 3,
                'placeholder' => 'Descrição'
            ],
            'options' => [
                'label' => 'Descrição'
            ]
        ]);

		$this->add([
			'name' => 'link',
			'attributes' => [
				'placeholder' => 'http://'
			],
            'options' => [
                'label' => 'Link'
            ]
		]);

		$this->add([
			'name' => 'credits',
            'options' => [
                'label' => 'Créditos'
            ]
		]);

		$this->add([
			'name' => 'start_date',
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'attributes' => [
				''
			],
            'options' => [
                'label' => 'Data de início'
            ]
		]);

		$this->add([
			'name' => 'end_date',
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'attributes' => [
				''
			],
            'options' => [
                'label' => 'Data de Término'
            ]
		]);

		$this->add([
			'name' => 'target_blank',
			'type' => 'checkbox',
			'options' => [
				'checked_value' => 1,
				'unchecked_value' => 0,
			],
			'attributes' => [
				'class' => 'icheck'
			]
		]);
	}

}