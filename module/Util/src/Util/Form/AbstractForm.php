<?php
namespace Util\Form;

use Zend\Form\Form;

abstract class AbstractForm extends Form
{
	public function __construct()
	{
		$this->initForm();
	}

	abstract public function initForm();
}