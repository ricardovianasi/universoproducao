<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 26/02/2016
 * Time: 09:34
 */

namespace Admin\Form\Login;


use Admin\Form\Account\ChangePasswordForm;

class UpdatePassword extends ChangePasswordForm
{
	public function __construct()
	{
		parent::__construct();

		$this->add([
			'name' => 'email',
			'options' => [
				'label' => 'Email'
			],
			'attributes' => [
				'readonly' => 'readonly'
			]
		]);
	}

}