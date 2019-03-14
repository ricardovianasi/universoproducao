<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 30/10/2017
 * Time: 11:08
 */

namespace Admin\Form\Movie;

use Zend\Form\Fieldset;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;

class ProducingInstitutionFieldset extends Fieldset
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
            'name' => 'name',
            'options' => [
                'label' => 'Nome da instituição',
                /*'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]*/
            ],
            'attributes' => [
//                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'address',
            'options' => [
                'label' => 'Endereço',
                /*'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]*/
            ],
            'attributes' => [
//                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'country',
            'options' => [
                'label' => 'País',
                /*'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]*/
            ],
            'attributes' => [
//                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'uf',
            'options' => [
                'label' => 'UF',
                /*'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]*/
            ],
            'attributes' => [
//                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'phone',
            'options' => [
                'label' => 'Telefone fixo',
                /*'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]*/
            ],
            'attributes' => [
//                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'mobile_phone',
            'options' => [
                'label' => 'Telefone celular',
                /*'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]*/
            ],
            'attributes' => [
//                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'Email',
                /*'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]*/
            ],
            'attributes' => [
//                'required' => 'required'
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'name' => [
                'name' => 'name',
                'required' => false,
            ],
            'address' => [
                'name' => 'address',
                'required' => false,
            ],
            'uf' => [
                'name' => 'uf',
                'required' => false,
            ],
            'phone' => [
                'name' => 'phone',
                'required' => false,
            ],
            'mobile_phone' => [
                'name' => 'mobile_phone',
                'required' => false,
            ],
            'email' => [
                'name' => 'email',
                'required' => false,
            ],
        ];
    }


}