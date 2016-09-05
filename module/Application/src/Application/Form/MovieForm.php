<?php
namespace Application\Form;

use Zend\Form\Form;

class MovieForm extends Form
{
	public function __construct()
	{
		parent::__construct('movie-form');
		$this->setAttributes([
			'method' => 'POST'
		]);

		$this->add([

		]);


	}

}