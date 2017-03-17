<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/01/2016
 * Time: 10:30
 */

namespace Util\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Cnpj extends AbstractHelper
{
	public function __invoke($value=null)
	{
		if(!$value) {
			return '';
		}

        $value = preg_replace('/[^0-9]/', '', $value);

		return $this->render($value);
	}

	public function render($value)
	{
		return sprintf('%s.%s.%s/%s-%s',
			substr($value, 0, 2),
			substr($value, 2, 3),
			substr($value, 5, 3),
			substr($value, 8, 4),
			substr($value, 12, 2)
		);
	}

}