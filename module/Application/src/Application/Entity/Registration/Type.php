<?php
namespace Application\Entity\Registration;

class Type
{
    const MOVIE                 = 'movie';
    const WORKSHOP              = 'workshop';
    const SEMINAR               = 'seminar';
    const PROJECT               = 'project';
    const EDUCATIONAL_PROJECT   = 'educational_project';

	static public function toArray() {
		return array(
		    self::MOVIE => 'Filme',
            self::WORKSHOP => 'Oficina',
            self::SEMINAR => 'Seminário',
            self::PROJECT => 'Projeto',
            self::EDUCATIONAL_PROJECT => 'Projetos audiovisuaus educativos'
		);
	}

	static public function get($op) {
		if(array_key_exists($op, self::toArray())) {
			return self::toArray()[$op];
		}
		return null;
	}
}