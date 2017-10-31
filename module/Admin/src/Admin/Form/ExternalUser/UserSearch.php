<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 07/01/2016
 * Time: 14:39
 */

namespace Admin\Form\ExternalUser;

use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class UserSearch extends Form
{
	public function __construct()
	{
		parent::__construct('user-search');

		$this->setAttributes([
			'id' => 'user-search',
			'class' => 'user-search',
			'method' => 'GET'
		]);

		$id = new Textarea('id');
		$this->add($id);

		$identifier = new Text('identifier');
		$identifier->setLabel('CPF, CNPJ ou PASSAPORTE')
			->setLabelAttributes(['class', 'input-sm']);
		$this->add($identifier);

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