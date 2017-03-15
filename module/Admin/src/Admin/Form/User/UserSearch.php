<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/02/2016
 * Time: 12:12
 */

namespace Admin\Form\User;

use Zend\Form\Form;

class UserSearch extends Form
{
	public function __construct()
	{
		parent::__construct('user-form-search');
		$this->setAttributes([
			'id' => 'user-form-search',
			'method' => 'GET'
		]);

		$this->add([
			'name' => 'name',
			'options' => [
				'label' => 'Nome',
			],
			'attributes' => [
				'class' => 'input-sm'
			]
		]);

		$this->add([
			'name' => 'email',
			'options' => [
				'label' => 'email',
			],
			'attributes' => [
				'class' => 'input-sm'
			]
		]);

		$this->add([
			'name' => 'is_administrator',
			'type' => 'select',
			'options' => [
				'empty_option' => 'Selecione',
				'value_options' => [
					1 => 'Sim',
					2 => 'Não'
				]
			],
			'attributes' => [
				'class' => 'input-sm'
			]
		]);
	}

}