<?php
namespace Admin\Form\Tv;

use Zend\Form\Form;

class TvForm extends Form
{
	public function __construct()
	{
		parent::__construct();

		$this->add([
			'name' => 'caption',
			'options' => [
				'label' => 'Descrição'
			],
			'attributes' => [
				'placeholder' => 'Informe uma Legenda'
			]
		]);

		$this->add([
			'name' => 'youtube',
			'options' => [
				'label' => 'Url do Vídeo'
			],
			'attributes' => [
				'placeholder' => 'http://'
			]
		]);

		$this->add([
			'name' => 'date',
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'options' => [
				'label' => 'Data'
			],
			'attributes' => [
				''
			]
		]);
	}

}