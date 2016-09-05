<?php
namespace Admin\Form\Event;

use Application\Entity\Event\EventType;
use TwbBundle\Form\Element\Tinymce;
use Zend\Form\Form;

class EventForm extends Form
{
	public function __construct()
	{
		parent::__construct('event-form');
		$this->setAttributes([
			'method', 'POST',
			'class' => 'form-horizontal'
		]);

		$this->add([
			'name' => 'full_name',
			'options' => [
				'label' => 'Nome do Completo'
			]
		]);

		$this->add([
			'name' => 'short_name',
			'options' => [
				'label' => 'Nome Abreviado'
			]
		]);

		$this->add([
			'name' => 'edition',
			'options' => [
				'label' => 'Número da Edição'
			]
		]);

		$this->add([
			'name' => 'start_date',
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'options' => [
				'label' => 'Data de Início',

			],
			'attributtes' => [
				'readonly' => TRUE,
			]
		]);

		$this->add([
			'name' => 'end_date',
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'options' => [
				'label' => 'Data de Término',
			],
			'attributtes' => [
				'readonly' => TRUE,
			]
		]);

		$this->add([
			'name' => 'type',
			'type' => 'select',
			'options' => [
				'label' => 'Tipo do Evento',
				'empty_option' => 'Selecione',
				'value_options' => EventType::toArray()
			]

		]);

		$this->add([
			'name' => 'description',
			'type' => Tinymce::class,
			'options' => [
				'label' => 'Descrição'
			],
			'attributtes' => [
				'id' => 'tinymce'
			]
		]);
	}
}