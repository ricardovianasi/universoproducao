<?php
namespace Application\Entity\Widget;

final class Type
{
    const GALLERY = 'gallery';

    static public function toArray() {
        return array(
            self::GALLERY => 'Galeria'
        );
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }
}