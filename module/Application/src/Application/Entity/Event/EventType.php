<?php
namespace Application\Entity\Event;

final class EventType
{
    const MOSTRATIRADENTES = 'mostratiradentes';

	static public function toArray() {
		return array(
			self::MOSTRATIRADENTES      => 'Mostra Tiradentes',
            'mostratiradentes_sp'   => 'Mostra Tiradentes SP',
			'cineop'                => 'Mostra CineOP',
			'cinebh'                => 'Mostra CineBH',
            'mostra'                => 'Mostra',
            'arquivoemcartaz'       => 'Arquivo em Cartaz'
		);
	}

	static public function get($op) {
		if(array_key_exists($op, self::toArray())) {
			return self::toArray()[$op];
		}
		return null;
	}
}