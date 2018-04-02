<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 20/02/2018
 * Time: 08:49
 */

namespace Admin\Form\Project;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;

class PeopleFieldset extends Fieldset
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
                'label' => 'Nome'
            ],
            'attributes' => [
                'class' => 'people-name',
                'required' => 'required'
            ]
        ]);

        $this->add([

            'name' => 'address',
            'options' => [
                'label' => 'Endereço'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'phone',
            'options' => [
                'label' => 'Telefone'
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
            'type' => 'Textarea',
            'name' => 'description',
            'options' => [
                'label' => 'Descrição'
            ],
            'attributes' => [
                'rows' => '3',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'file',
            'name' => 'image',
            'options' => [
                'label' => 'Foto',
                'help-block' => 'mínimo 800kb e máximo de 2mb'
            ],
            'attributes' => [
                'attributes' => [
                    'accept' => 'image/*',
                ],
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'image' => [
                'name' => 'image',
                'required' => true,
                'validators' => [
                    new MimeType('image/png,image/jpg,image/jpeg'),
                    [
                        'name' => Size::class,
                        'options' => [
                            'max' => '2MB',
                            'min' => '800KB',
                            'messages' => [
                                Size::TOO_SMALL => "O tamanho mínimo do arquivo é 800KB",
                                Size::TOO_BIG => "O tamanho máximo do arquivo é 2MB"
                            ]
                        ]
                    ],
                ]
            ],
        ];
    }
}