<?php
namespace Admin\Form\Accreditation;


use Admin\EntityManagerTrait;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class AccreditationForm extends Form
{
	use EntityManagerTrait;

	public function __construct($name, array $options)
	{
		parent::__construct('accreditation-form');
		$this->setAttributes([
			'method' => 'POST',
			'class' => 'form',
			'id' => 'accreditation-form'
		]);
		
		$this->add([
			'name' => 'vehicle_name',
			'options' => [
				'label' => 'Nome do Veículo',
			],
			'attributes' => [
				'required' => 'required'
			],
		]);
		
		
	}

}