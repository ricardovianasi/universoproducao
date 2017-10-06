<?php
namespace MeuUniverso\Form;

use Application\Entity\User\User;

class ValidateUserForm extends \Admin\Form\ExternalUser\UserForm
{
    public function __construct($em, $type=User::TYPE_PESSOA_FISICA)
    {
        parent::__construct($em, $type);

        $this->setAttribute('data-js-validate', '');

        $this->get('identifier')->setAttributes([
            'disabled' => 'disabled',
            'required' => false
        ]);
        $this->getInputFilter()->get('identifier')->setRequired(false);

        $this->get('email')->setAttributes([
            'disabled' => 'disabled',
            'required' => false
        ]);
        $this->getInputFilter()->get('email')->setRequired(false);
    }
}