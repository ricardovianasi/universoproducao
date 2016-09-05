<?php
namespace Admin\Form\User;

use Zend\Form\Form;

class ProfileForm extends Form
{
	public function __construct()
	{
		parent::__construct('profile-form');
		$this->setAttributes([
			'method' => 'POST',
			'class' => 'default-form-actions'
		]);

		$this->add([
			'name' => 'name',
			'type' => 'text',
			'options' => [
				'label' => 'Nome do perfil',
			],
			'attributes' => [
				'required' => 'required'
			]
		]);
	}
}