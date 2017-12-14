<?php
namespace Application\Entity\Notify;

final class NotifyStatus
{
    const ENABLE = 'enable';
    const DISABLE = 'disable';
    const STANDBY = 'standby';

    static public function toArray() {
        return array(
            self::ENABLE => 'Habilitado',
            self::DISABLE => 'Desabilitado'
        );
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }
}