<?php
namespace MeuUniverso\Form;

use Application\Entity\User\User;

class UserForm extends \Admin\Form\ExternalUser\UserForm
{
    public function __construct($em, $type=User::TYPE_PESSOA_FISICA)
    {
        parent::__construct($em, $type);

        $this->get('identifier')->setAttributes([
            'disabled' => 'disabled',
            'required' => false
        ]);
        $this->getInputFilter()->get('identifier')->setRequired(false);
    }
}