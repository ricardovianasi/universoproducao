<?php
namespace Application\Entity\Message;

use Application\Entity\Registration\Type;

final class MessageType extends Type
{
    //Meu universo user messages
    const USER_CREATED_LOGIN        = 'user_created_login';
    const USER_RECOVER_PASS         = 'user_recover_pass';
    const USER_CHANGE_PASS          = 'user_change_pass';

    static public function toArray() {
        return array(
            self::USER_CREATED_LOGIN => 'Usuário - criação de usuário',
            self::USER_RECOVER_PASS => 'Usuário - recuperação de senha',
            self::USER_CHANGE_PASS => 'Usuário - atualização de senha'
        );
    }

    static public function get($op) {
        if(array_key_exists($op, self::toArray())) {
            return self::toArray()[$op];
        }
        return null;
    }

    static public function getVariablesByType($op)
    {
        $vars = [
            'user' => [
                ['#user_name#' => 'Nome do usuário'],
                ['#user_alias#' => 'Apelido do usuário'],
                ['#user_email#' => 'E-mail do usuário'],
                ['#user_identifier#' => 'Identificador do usuário'],
                ['#user_url' => 'URL de confirmação']
            ],
        ];

        $varsByType = [
            self::USER_CREATED_LOGIN => $vars['user'],
            self::USER_RECOVER_PASS => $vars['user'],
            self::USER_CHANGE_PASS => $vars['user'],
        ];

        if($op && array_key_exists($op, $varsByType)) {
            return $varsByType[$op];
        }

        return false;
    }
}