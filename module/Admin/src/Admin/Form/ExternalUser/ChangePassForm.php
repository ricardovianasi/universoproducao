<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 02/02/2016
 * Time: 09:57
 */

namespace Admin\Form\ExternalUser;


use Zend\Form\Element\Button;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class ChangePassForm extends Form
{
	public function __construct()
	{
		parent::__construct('user-change-pass-form');
		$this->setAttributes([
			'method' => 'POST',
			'class' => 'user-change-pass-form default-form-actions',
			'id' => 'user-change-pass-form'
		]);

		$oldLogin = new Text('old_login');
		$oldLogin->setLabel('Login');
		$oldLogin->setAttribute('readonly', 'readonly');
		$this->add($oldLogin);

		$oldPass = new Text('old_pass');
		$oldPass->setLabel('Senha');
		$oldPass->setAttribute('readonly', 'readonly');
		$this->add($oldPass);

		$btnGenPass = new Button('gem-pass');
		$btnGenPass->setValue('Gerar nova senha')
			->setAttribute('class', 'btn btn-success ')
			->setOption('fontAwesome', 'fa fa-key');
		$tempPass = new Text('temp_password');
		$tempPass->setLabel('Senha Temporária')
		->setAttribute('readonly', 'readonly')
		->setOption('add-on-append', $btnGenPass);
		$this->add($tempPass);

	}

}