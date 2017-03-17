<?php

namespace Util\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class Cnpj extends AbstractValidator
{
	const INVALID = 'invalid';

	protected $messageTemplates = array(
		self::INVALID => "'%value%' não é um CNPF válido"
	);

	public function isValid($value)
	{
		$this->setValue($value);
		$value = preg_replace('/[^0-9]/', '', $value);

		if(!$this->validate($value)) {
			$this->error(self::INVALID);
			return false;
		}

		return true;
	}

	protected function validate($value)
	{
        // Valida tamanho
        if (strlen($value) != 14)
            return false;

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $value{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($value{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $value{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $value{13} == ($resto < 2 ? 0 : 11 - $resto);
	}
}