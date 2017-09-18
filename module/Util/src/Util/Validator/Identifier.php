<?php

namespace Util\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class Identifier extends AbstractValidator
{
	const INVALID = 'invalid';

	protected $messageTemplates = array(
		self::INVALID => "'%value%' não é um identificador válido"
	);

	public function isValid($value)
	{
		$this->setValue($value);
		$value = preg_replace('/[^0-9A-Ba-b]/', '', $value);

		if(is_numeric($value)) {
            $validated = ($this->validateCNPJ($value) || $this->validateCPF($value));
        } else {
            $validated = $this->validatePassport($value);
        }

		if(!$validated) {
			$this->error(self::INVALID);
			return false;
		}

		return true;
	}

	protected function validateCPF($value)
	{
        if(
            strlen($value) != 11
            || preg_match('/0{11}|1{11}|2{11}|3{11}|4{11}|5{11}|6{11}|7{11}|8{11}|9{11}$/', $value)
            || $value == '01234567890'
        ) {
            return false;
        }

        $soma=0;
        for($a=0; $a<9; $a++) $soma += (10-$a) * $value[$a];
        $dig1 = $soma%11<2 ? 0 : 11 - $soma%11;
        if($dig1 != $value[9]) return false;

        $soma=0;
        for ($a=0; $a<10; $a++) $soma += (11-$a) * $value[$a];
        $dig2 = $soma%11<2 ? 0 : 11 - $soma%11;
        if($dig2 != $value[10]) return false;

        $soma=0;
        for ($a=0; $a<10; $a++) $soma += (11-$a) * $value[$a];
        $dig2 = $soma%11<2 ? 0 : 11 - $soma%11;
        if($dig2 != $value[10]) return false;

        return true;
	}

    protected function validateCNPJ($value)
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

    protected function validatePassport($value)
    {
        return true;
    }
}