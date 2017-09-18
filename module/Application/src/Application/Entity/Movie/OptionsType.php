<?php
namespace Application\Entity\Movie;

final class OptionsType
{



	static public function toArray() {
		return array(
		);
	}

	static public function get($op) {
		if(array_key_exists($op, self::toArray())) {
			return self::toArray()[$op];
		}
		return null;
	}
}