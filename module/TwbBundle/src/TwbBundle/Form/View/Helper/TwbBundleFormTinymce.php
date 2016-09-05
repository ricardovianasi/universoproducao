<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 11:00
 */

namespace TwbBundle\Form\View\Helper;


use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormTextarea;

class TwbBundleFormTinymce extends FormTextarea
{
	public function render(ElementInterface $element)
	{
		if ($sElementClass = $element->getAttribute('class')) {
			if (!preg_match('/(\s|^)tinymce(\s|$)/', $sElementClass)) {
				$element->setAttribute('class', trim($sElementClass . ' tinymce'));
			}
		} else {
			$element->setAttribute('class', 'tinymce');
		}

		return parent::render($element);
	}
}