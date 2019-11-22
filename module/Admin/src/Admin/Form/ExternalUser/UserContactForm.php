<?php
namespace Admin\Form\ExternalUser;

class UserContactForm extends UserForm
{

    public function __construct($em, $type = 'all')
    {
        parent::__construct($em, $type);

        $this->get('identifier')->setAttribute('required', false);
        $this->get('origin')->setAttribute('required', 'required');
        $this->get('category')->setAttribute('required', 'required');
        $this->get('subcategory')->setAttribute('required', 'required');

        $this->getInputFilter()->get('identifier')->setRequired(false);
        $this->getInputFilter()->get('origin')->setRequired(true);
        $this->getInputFilter()->get('category')->setRequired(true);
        $this->getInputFilter()->get('subcategory')->setRequired(true);
    }
}