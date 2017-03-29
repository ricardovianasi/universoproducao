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
                'placeholder' => 'Informe um Nome',
                'class' => 'input-lg'
            ]
        ]);

        $this->add([
            'name'=>'slug',
            'type'=>'hidden'
        ]);

        $this->add([
            'name' => 'is_visible',
            'type' => 'select',
            'options' => [
                'label' => 'Exibir categoria',
                'value_options' => [
                    1 => 'Sim',
                    0 => 'Não'
                ]
            ],
            'value' => 1
        ]);

    }
}