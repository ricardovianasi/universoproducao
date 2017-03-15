<?php

namespace Admin\Form\Login;

use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class LoginForm extends Form
{

	public function __construct()
	{
		parent::__construct('loginForm');
		$this->setAttributes([
			'method' => 'POST',
			'class' => 'login-form'
		]);

		$userName = new Text('email');
		$userName->setLabel('Email')
			->setAttributes([
				'autocomplete' => 'true',
				'placeholder' => 'Login',
				'class' => 'form-control placeholder-no-fix'
			]);

		$pass = new Password('password');
		$pass->setLabel('Senha')
			->setAttributes([
				'autocomplete' => 'off',
				'placeholder' => 'Senha',
				'class' => 'form-control placeholder-no-fix'
			]);

		$this->add($userName)->add($pass);
	}
}