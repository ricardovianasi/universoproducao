<?php
namespace MeuUniverso\Form;

class UserForm extends \Admin\Form\ExternalUser\UserForm
{
    public function __construct($em)
    {
        parent::__construct($em);

        $this->get('identifier')->setAttributes([
            'disabled' => 'disabled',
            'required' => false
        ]);

    }
}