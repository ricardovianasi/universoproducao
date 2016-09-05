<?php
namespace TwbBundle\Form\Element;

use Zend\Form\Element\Text;

/**
 * Date Picker form element
 *
 * @author Ricardo Viana
 *
 */
class DatePicker extends Text
{
	protected $options = [
		'php-date-format' => 'd/m/Y'
	];
}