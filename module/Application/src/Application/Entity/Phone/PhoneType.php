<?php
namespace Application\Entity\Phone;

class PhoneType
{
    static public function toArray() {
        return array(
            'mobile'        => 'Celular',
            'residential'   => 'Residencial',
            'commercial'    => 'Comercial',
            'other'         => 'Outro'
        );
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }
}