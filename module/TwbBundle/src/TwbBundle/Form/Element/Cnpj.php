<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/01/2016
 * Time: 10:53
 */
namespace TwbBundle\Form\Element;

use Zend\Form\Element\Text;
use Zend\InputFilter\InputProviderInterface;

class Cnpj extends Text implements InputProviderInterface
{
	/**
	 * @var ValidatorInterface
	 */
	protected $validator;

	/**
	 * Get validator
	 *
	 * @return ValidatorInterface
	 */
	protected function getValidator()
	{
		return $this->validator;
	}

	/**
	 * Provide default input rules for this element
	 *
	 * Attaches an email validator.
	 *
	 * @return array
	 */
	public function getInputSpecification()
	{
		return [
			'name' => $this->getName(),
			'required' => true,
			'filters' => [
				['name' => 'Zend\Filter\StringTrim'],
				['name' => 'Zend\Filter\Digits']
			],
			'validators' => [
				new \Util\Validator\Cnpj()
			]
		];
	}
}