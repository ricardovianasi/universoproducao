<?php
namespace Application\Entity\Programing;

use Application\Entity\Registration\Type as RegistrationType;

class Type extends RegistrationType
{
    const SESSION           = 'session';
    const ART               = 'art';
    const SEMINAR_DEBATE    = 'seminar_debate';
    const OPENING           = 'opening';
    const CLOSING           = 'closing';
    const OTHER             = 'other';

    static function toArray()
    {
        $toArray = parent::toArray();
        $toArray[self::ART] = 'Arte';
        $toArray[self::SEMINAR_DEBATE] = 'Debate';
        $toArray[self::OPENING] = 'Abertura';
        $toArray[self::CLOSING] = 'Encerramento';
        $toArray[self::OTHER] = 'Outro';

        return $toArray;
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }
}