<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 07/12/2015
 * Time: 09:07
 */

namespace TwbBundle\Form\Element;


use Zend\Form\Element\Text;

class DateTimePicker extends Text
{
	private $format = 'd/m/Y H:i';
}