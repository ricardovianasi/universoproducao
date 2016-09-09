<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 11:00
 */

namespace TwbBundle\Form\View\Helper;


use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormText;

class TwbBundleFormTagsInput extends FormText
{
	public function render(ElementInterface $element)
	{
	    if(!$element->hasAttribute('data-role'))
	        $element->setAttribute('data-role', 'tagsinput');

		return parent::render($element); // TODO: Change the autogenerated stub
	}
}