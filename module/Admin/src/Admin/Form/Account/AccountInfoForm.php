<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 06/01/2016
 * Time: 09:49
 */

namespace Admin\Form\Account;

use Zend\Form\Element\Text;
use Zend\Form\Form;

class AccountInfoForm extends Form
{
	public function __construct()
	{
		parent::__construct('post-info-form');
		$this->setAttributes([
			'method' => 'POST',
			'class' => 'enable-validators default-form-actions'
		]);

		$name = new Text('name');
		$name->setLabel('Nome Completo');
		$name->setAttribute('required', 'required');
		$this->add($name);

		$alias = new Text('alias');
		$alias->setLabel('Como você gostaria de ser chamado (apelido)');
		$alias->setAttribute('required', 'required');
		$this->add($alias);

		$email = new Text('email');
		$email->setLabel('Email');
		$email->setAttribute('required', 'required');
		$this->add($email);

		$occupation = new Text('occupation');
		$occupation->setLabel('Ocupação');
		$occupation->setAttribute('required', 'required');
		$this->add($occupation);

		$phone = new Text('phone');
		$phone->setLabel('Telefone');
		$phone->setAttribute('required', 'required');
		$this->add($phone);
	}
}