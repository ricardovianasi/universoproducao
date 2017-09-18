<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 10/09/2017
 * Time: 14:28
 */

namespace Application\Entity\User;


final class type
{
    const PF = 'pf';
    const PJ = 'pj';
    const INT = 'int';

    static public function toArray() {
        return array(
            self::PF => 'Pessoa física',
            self::PJ => 'Pessoa jurídica',
            self::INT => 'Cadastro internacional'
        );
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }

}