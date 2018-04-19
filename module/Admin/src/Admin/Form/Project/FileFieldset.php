<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 22/02/2018
 * Time: 09:21
 */

namespace Admin\Form\Project;

use Admin\Form\MediaFieldset;
use Zend\Validator\File\Size;

class FileFieldset extends MediaFieldset
{
    public function getInputFilterSpecification()
    {
        /*$inputFilterSpecification = parent::getInputFilterSpecification();
        return array_merge($inputFilterSpecification, [
            'file' => [
                'name' => 'file',
                'required' => false,
                'validators' => [
                    [
                        'name' => Size::class,
                        'options' => [
                            'max' => '2MB',
                            'messages' => [
                                Size::TOO_BIG => "O tamanho máximo do arquivo é 2MB"
                            ]
                        ]
                    ],
                ]
            ]
        ]);*/
        $inputFilterSpecification = parent::getInputFilterSpecification();
        return $inputFilterSpecification;
    }

}