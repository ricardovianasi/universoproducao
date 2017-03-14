<?php
namespace Admin\Form\Channel;

use Zend\Form\Form;

class CategoryForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome'
            ],
            'attributes' => [
                'placeholder' => 'Informe um Nome'
            ]
        ]);
    }
}