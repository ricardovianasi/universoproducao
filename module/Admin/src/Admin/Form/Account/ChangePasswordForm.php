<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 06/01/2016
 * Time: 11:04
 */

namespace Admin\Form\Account;

use Zend\Form\Element\Password;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilter;

class ChangePasswordForm extends Form
{
	public function __construct()
	{
		parent::__construct('account-change-password');
		$this->setAttributes([
			'method' => 'POST'
		]);

		$currentPassword = new Password('currentPassword');
		$currentPassword->setLabel('Senha Atual');
		$this->add($currentPassword);

		$newPassword = new Password('newPassword');
		$newPassword->setLabel('Nova Senha');
		$this->add($newPassword);

		$reNewPassword = new Password('reNewPassword');
		$reNewPassword->setLabel('Confirme a Nova Senha');
		$this->add($reNewPassword);

		$inputFilter = new InputFilter();

		$inputFilter->add([
			'name' => 'currentPassword',
			'required' => true
		]);

		$inputFilter->add([
			'name' => 'newPassword',
			'required' => true
		]);

		$inputFilter->add([
			'name' => 'reNewPassword',
			'required' => true,
			'validators' => [
				[
					'name' => 'Identical',
					'options' => [
						'token' => 'newPassword'
					]
				]
			]
		]);

		$this->setInputFilter($inputFilter);
	}

}