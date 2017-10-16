<?php
namespace Application\Entity\Registration;

final class Status
{
    const ON_EVALUATION     = 'on_evaluation';
    const SELECTED          = 'selected';
    const NOT_SELECTED      = 'not_selected';
    const STANDBY           = 'standby';

    static public function toArray() {
        return array(
            self::ON_EVALUATION => 'Em processo de seleção',
            self::SELECTED => 'Selecionado',
            self::NOT_SELECTED => 'Não selecionado',
            self::NOT_SELECTED => 'Standby',
        );
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }
}