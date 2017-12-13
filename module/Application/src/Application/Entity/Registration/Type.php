<?php
namespace Application\Entity\Registration;

class Type
{
    const MOVIE     = 'movie';
    const WORKSHOP  = 'workshop';
    const SEMINAR   = 'seminar';

	static public function toArray() {
		return array(
		    self::MOVIE => 'Filme',
            self::WORKSHOP => 'Oficina',
            self::SEMINAR => 'Seminário',
		);
	}

	static public function get($op) {
		if(array_key_exists($op, self::toArray())) {
			return self::toArray()[$op];
		}
		return null;
	}
}