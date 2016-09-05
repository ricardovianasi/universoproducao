<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/01/2016
 * Time: 10:30
 */

namespace Util\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Cpf extends AbstractHelper
{
	public function __invoke($value=null)
	{
		if(!$value) {
			return '';
		}

		return $this->render($value);

	}

	public function render($value)
	{
		return sprintf('%s.%s.%s-%s',
			substr($value, 0, 3),
			substr($value, 3, 3),
			substr($value, 6, 3),
			substr($value, 9, 2)
		);
	}

}