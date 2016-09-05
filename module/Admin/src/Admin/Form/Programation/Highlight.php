<?php
namespace Admin\Form\Programation;


use Zend\Form\Form;

class Highlight extends Form
{
	public function __construct()
	{
		parent::__construct();
		$this->setAttributes([
			'method', 'POST',
		]);

		$this->add([
			'name' => 'date',
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'options' => [
				'label' => 'Data'
			],
			'attributes' => [
			]
		]);

		$this->add([
			'name' => 'hour',
			'options' => [
				'label' => 'Hora'
			],
			'attributes' => [
				'placeholder' => 'Horário de início e fim'
			]
		]);

		$this->add([
			'name' => 'title',
			'options' => [
				'label' => 'Título Principal'
			],
			'attributes' => [
				'placeholder' => 'Título principal'
			]
		]);

		$this->add([
			'name' => 'subtitle',
			'options' => [
				'label' => 'Subtítulo'
			],
			'attributes' => [
				'placeholder' => 'Subtítulo'
			]
		]);

		$this->add([
			'name' => 'position',
			'type' => 'select',
			'options' => [
				'label' => 'Posição',
				'empty_option' => 'Selecione',
				'value_options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				]
			],
		]);

		$this->add([
			'name' => 'place',
			'options' => [
				'label' => 'Local'
			],
			'attributes' => [
				'placeholder' => 'Local'
			]
		]);

		$this->add([
			'name' => 'direction',
			'options' => [
				'label' => 'Direção'
			],
			'attributes' => [
				'placeholder' => 'Direção(se houver)'
			]
		]);

		$this->add([
			'name' => 'url',
			'options' => [
				'label' => 'Url do Item'
			],
			'attributes' => [
				'placeholder' => 'Http://'
			]
		]);

		$this->add([
			'name' => 'is_highlight',
			'type' => 'checkbox',
			'options' => [
				'checked_value' => 1,
				'unchecked_value' => 0,
				'label' => "Destaque Principal"
			],
			'attributes' => [
				'class' => 'icheck'
			]
		]);

		$this->add([
			'name' => 'photo',
			'options' => [
				'label' => 'Imagem de Destaque'
			],
			'attributes' => [
				'placeholder' => 'Imagem de Destaque',
				'id' => 'photo'
			]
		]);
	}
}