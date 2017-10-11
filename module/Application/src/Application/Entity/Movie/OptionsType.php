<?php
namespace Application\Entity\Movie;

final class OptionsType
{
    const CLASSIFICATION            = 'classification';
    const FORMAT_COMPLETED          = 'format_completed';
    const CATEGORY                  = 'category';
    const WINDOW                    = 'window';
    const SOUND                     = 'sound';
    const COLOR                     = 'color';
    const GENRE                     = 'genre';
    const ACCESSIBILITY             = 'accessibility';
    const FEATURE_DIRECTED          = 'feature_directed';
    const SHORT_MOVIE_CATEGORY      = 'short_movie_category';

	static public function toArray() {
		return array(
		    self::CLASSIFICATION => 'Classificação',
            self::FORMAT_COMPLETED => 'Formato de finalização',
            self::CATEGORY => 'Categoria',
            self::WINDOW => 'Janela de exibição',
            self::SOUND => 'Som',
            self::COLOR => 'Cor',
            self::GENRE => 'Gênero',
            self::ACCESSIBILITY => 'Acessibilidade',
            self::FEATURE_DIRECTED => 'Longas dirigidos',
            self::SHORT_MOVIE_CATEGORY => 'Categoria de curtas'
		);
	}

	static public function get($op) {
		if(array_key_exists($op, self::toArray())) {
			return self::toArray()[$op];
		}
		return null;
	}
}