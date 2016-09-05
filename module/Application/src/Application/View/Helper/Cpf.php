<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Cpf extends AbstractHelper
{
	public function __invoke($str)
	{
		$format  = substr( $str, 0, 3 ) . '.';
		$format .= substr( $str, 3, 3 ) . '.';
		$format .= substr( $str, 6, 3 ) . '-';
		$format .= substr( $str, 9, 2 ) . '';

		return $format;
	}

}