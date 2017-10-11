<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 11/10/2017
 * Time: 11:38
 */

namespace Application\Entity\Movie;


final class Category
{
    const LONGA = 'longa';
    const CURTA = 'curta';
    const MEDIA = 'media';

    static public function toArray()
    {
        return [
            self::CURTA => 'Curta',
            self::MEDIA => 'Média',
            self::LONGA => 'Longa'
        ];
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }
}