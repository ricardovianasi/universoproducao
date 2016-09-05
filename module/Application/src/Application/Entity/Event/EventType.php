<?php
namespace Application\Entity\Event;

final class EventType
{

	static public function toArray() {
		return array(
			'mostratiradentes'      => 'Mostra Tiradentes',
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