<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 30/12/2015
 * Time: 10:26
 */

namespace Util\Security;

use Zend\Crypt\Password\Bcrypt;

final class Crypt
{
	public static $instance;

	/**
	 * The salt code
	 *
	 * @var string
	 */
	private $salt = '[universo#producao]%[SisAdmin2015]%[a51]+';

	/**
	 * Bcrypt instance
	 *
	 * @var Bcrypt
	 */
	private $bcrypt;

	private function __clone() {}

	private function __construct()
	{
		$this->bcrypt = new Bcrypt([
			$this->salt
		]);
	}

	/**
	 * Retorna uma instancia de Crypt
	 *
	 * @return Crypt
	 */
	static public function getInstance()
	{
		if(null === self::$instance) {
			self::$instance = new Crypt();
		}

		return self::$instance;
	}

	public function generateEncryptPass($str)
	{
		return $this->bcrypt->create($str);
	}

	public function testPass($pass, $hash)
	{
		return $this->bcrypt->verify($pass, $hash);
	}
	
	static public function makePassword($lenght = 8, $uppercase = true, $number = true, $specialChar = true)
	{
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';
		$caracteres .= $lmin;
		if ($uppercase) $caracteres .= $lmai;
		if ($number) $caracteres .= $num;
		if ($specialChar) $caracteres .= $simb;
		$len = strlen($caracteres);
		for ($n = 1; $n <= $lenght; $n++) {
			$rand = mt_rand(1, $len);
			$retorno .= $caracteres[$rand-1];
		}
		return $retorno;
	}
}