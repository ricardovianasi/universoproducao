<?php
namespace Admin\Form\Movie;

use Admin\Form\EntityManagerTrait;
use Application\Entity\Channel\Category;
use Application\Entity\Movie\Options;
use Application\Entity\Movie\OptionsType;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class OptionsForm extends Form
{
    use EntityManagerTrait;

    public function __construct($em=null)
    {
        if($em) {
            $this->entityManager = $em;
        }

        parent::__construct();
        $this->setAttributes([
            'method'=>'POST',
            'class' => 'form-horizontal'
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome'
            ],
            'attributes' => [
                'placeholder' => 'Informe um nome'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'help_block',
            'options' => [
                'label' => 'Texto de ajuda'
            ],
            'attributes' => [
                'rows' => '5'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Selecione o status',
                'value_options' => [
                    Options::STATUS_ENABLED => 'Habilitado',
                    Options::STATUS_DISABLED => 'Desabilitado'
                ]
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'type',
            'options' => [
                'label' => 'Selecione um tipo',
                'value_options' => $this->populateTypes(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'id' => 'categories',
                'type'=>'select',
                'class' => 'form-control'
            ]
        ]);
    }

    public function populateTypes()
    {
        return OptionsType::toArray();
    }
}