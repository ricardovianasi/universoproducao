<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 22/02/2018
 * Time: 09:21
 */

namespace Admin\Form\Project;

use Admin\Form\MediaFieldset;
use Zend\Validator\File\Extension;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;

class ImageFieldset extends MediaFieldset
{
    public function getInputFilterSpecification()
    {
        return array(
            'file' => [
                'name' => 'file',
                'required' => true,
                'validators' => [
                    new MimeType('image/png,image/jpg,image/jpeg'),
                    new Extension('jpeg,jpg,png'),
                    [
                        'name' => Size::class,
                        'options' => [
                            'max' => '2MB',
                            'min' => '800kB',
                            'messages' => [
                                Size::TOO_SMALL => "O tamanho mínimo do arquivo é 800KB",
                                Size::TOO_BIG => "O tamanho máximo do arquivo é 2MB"
                            ]
                        ]
                    ],
                ]
            ],
            'is_default' => [
                'name' => 'is_default',
                'required' => false,
                'allow_empty' => true
            ]
        );
    }

}