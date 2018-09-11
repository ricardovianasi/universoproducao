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
    const SESSION_SCHOOL        = 'session_school';
    const MOTION_CITY_MOVIE     = 'motion_city_movie';
    const ARTISTIC_PROPOSAL     = 'artistic_proposal';
    const WORKSHOP_PROPOSAL     = 'workshop_proposal';

	static public function toArray() {
		return array(
		    self::MOVIE                 => 'Filme',
            self::WORKSHOP              => 'Oficina',
            self::SEMINAR               => 'Seminário',
            self::PROJECT               => 'Projeto',
            self::PROJECT_CINEMUNDI     => 'Projetos Brasil CineMundi',
            self::EDUCATIONAL_PROJECT   => 'Projetos audiovisuaus educativos',
            self::EDUCATIONAL_MOVIE     => 'Filmes Mostra Educação',
            self::SESSION_SCHOOL        => 'Sessão Cine escola',
            self::MOTION_CITY_MOVIE     => 'Filmes Mostra Cidade em Movimento',
            self::WORKSHOP_PROPOSAL     => 'Proposta de oficina',
            self::ARTISTIC_PROPOSAL     => 'Proposta artística'
		);
	}

	static public function get($op) {
		if(array_key_exists($op, self::toArray())) {
			return self::toArray()[$op];
		}
		return null;
	}
}