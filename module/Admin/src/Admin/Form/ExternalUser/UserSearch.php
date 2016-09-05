<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 07/01/2016
 * Time: 14:39
 */

namespace Admin\Form\ExternalUser;

use Zend\Form\Element\Text;
use Zend\Form\Form;

class UserSearch extends Form
{
	public function __construct()
	{
		parent::__construct('user-search');

		$this->setAttributes([
			'id' => 'user-search',
			'method' => 'GET'
		]);

		$cpf = new Text('cpf');
		$cpf->setLabel('CPF')
			->setLabelAttributes(['class', 'input-sm']);
		$this->add($cpf);

		$name = new Text('name');
		$name->setLabel('Nome')
			->setLabelAttributes(['class', 'input-sm']);
		$this->add($name);

		$email = new Text('email');
		$email->setLabel('Email')
			->setLabelAttributes(['class', 'input-sm']);
		$this->add($email);
	}

}