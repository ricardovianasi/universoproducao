<?php
namespace Application\Entity\Registration;

class Type
{
    const MOVIE                 = 'movie';
    const WORKSHOP              = 'workshop';
    const SEMINAR               = 'seminar';
    const PROJECT               = 'project';
    const PROJECT_CINEMUNDI     = 'project_cinemundi';
    const EDUCATIONAL_PROJECT   = 'educational_project';
    const EDUCATIONAL_MOVIE     = 'educational_movie';

	static public function toArray() {
		return array(
		    self::MOVIE                 => 'Filme',
            self::WORKSHOP              => 'Oficina',
            self::SEMINAR               => 'Seminário',
            self::PROJECT               => 'Projeto',
            self::PROJECT_CINEMUNDI     => 'Projetos Brasil CineMundi',
            self::EDUCATIONAL_PROJECT   => 'Projetos audiovisuaus educativos',
            self::EDUCATIONAL_MOVIE     => 'Filmes Mostra Educação'
		);
	}

	static public function get($op) {
		if(array_key_exists($op, self::toArray())) {
			return self::toArray()[$op];
		}
		return null;
	}
}