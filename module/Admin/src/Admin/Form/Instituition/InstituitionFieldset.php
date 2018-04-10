<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 20/02/2018
 * Time: 10:03
 */

namespace Admin\Form\Instituition;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class InstituitionFieldset extends Fieldset
    implements InputFilterProviderInterface
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'type' => 'hidden',
            'name' => 'id'
        ]);

        $this->add([
            'name' => 'social_name',
            'options' => [
                'label' => 'Razão social'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'fantasy_name',
            'options' => [
                'label' => 'Nome Fantasia'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'cnpj',
            'options' => [
                'label' => 'CNPJ'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'address',
            'options' => [
                'label' => 'Endereço'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'legal_representative',
            'options' => [
                'label' => 'Representante legal'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'phone',
            'options' => [
                'label' => 'Telefone comercial'
            ],
        ]);

        $this->add([
            'name' => 'mobile_phone',
            'options' => [
                'label' => 'Celular'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'E-mail'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'site',
            'options' => [
                'label' => 'Site'
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'description',
            'options' => [
                'label' => 'Breve apresentação',
                'help-block' => 'Máximo de 500 caracteres'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

    }

    public function getInputFilterSpecification()
    {
        return [];
    }
}