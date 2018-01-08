<?php
namespace Application\Entity\Registration;

final class Status
{
    const ON_EVALUATION     = 'on_evaluation';
    const SELECTED          = 'selected';
    const NOT_SELECTED      = 'not_selected';
    const STANDBY           = 'standby';
    const CONFIRMED         = 'confirmed';
    const NOT_CONFIRMED     = 'not_confirmed';

    static public function toArray() {
        return array(
            self::ON_EVALUATION     => 'Em processo de seleção',
            self::SELECTED          => 'Selecionado',
            self::NOT_SELECTED      => 'Não selecionado',
            self::STANDBY           => 'Standby',
            self::CONFIRMED         => 'Confirmado',
            self::NOT_CONFIRMED     => 'Não confirmado'
        );
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }
}