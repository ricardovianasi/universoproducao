<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/03/2016
 * Time: 12:06
 */

namespace Admin\Form;


use Zend\Form\Form;

class BannerForm extends Form
{
	public function __construct()
	{
		parent::__construct();

		$this->add([
			'name' => 'description',
			'type' => 'textarea',
			'attributes' => [
				'rows' => 2,
				'placeholder' => 'Informe um descrição'
			]
		]);
	}

}