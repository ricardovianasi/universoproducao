<?php
namespace MeuUniverso\Form;

class ValidateUserForm extends \Admin\Form\ExternalUser\UserForm
{
    public function __construct($em)
    {
        parent::__construct($em);

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