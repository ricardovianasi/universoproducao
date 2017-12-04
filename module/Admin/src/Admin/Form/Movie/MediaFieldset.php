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

class MediaFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'caption',
            'options' => [
                'label' => 'Créditos da foto'
            ],
            'attributes' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);

        $this->add([
            'type' => 'file',
            'name' => 'file',
            'attributes' => [
                'accept' => 'image/*',
            ],
            'options' => [
                'label' => 'Imagem'
            ],
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
            'attributes' => [
                'class' => 'image-collection-id'
            ]
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'src',
            'attributes' => [
                'class' => 'image-collection-src'
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'file' => [
                'name' => 'file',
                'required' => true,
                'validators' => [
                    new MimeType('image/png,image/jpg,image/jpeg'),
                    [
                        'name' => Size::class,
                        'options' => [
                            'min' => '800KB',
                            'max' => '2MB',
                            'messages' => [
                                Size::TOO_SMALL => "O tamanho mínimo do arquivo é 800KB",
                                Size::TOO_BIG => "O tamanho máximo do arquivo é 2MB"
                            ]
                        ]
                    ],
                ]
            ],
            'caption' => [
                'name'       => 'caption',
                'required'   => false,
            ],
        ];
    }


}