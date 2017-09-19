<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 10:14
 */

namespace MeuUniverso\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Identical;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class ChangePasswordForm extends Form
{
    public function __construct($oldPassRequired=false)
    {
        parent::__construct('change-pass-form');
        $this->setAttributes([
            'method' => 'POST',
            'data-js-validate' => '',
            'class' => 'userlogin-form'
        ]);

        $this->add([
            'name' => 'password',
            'required' => true,
            'type' => 'Password',
            'options' => [
                'label' => 'Senha',
                'help-block' => 'Mínimo de 8 caracteres. Necessário incluir 1 número, 1 letra e 1 caractere especial',
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'password',
                'data-parsley-minlength' => "8",
                'data-parsley-number' => "1",
                'data-parsley-special' => "1",
                'data-parsley-char' => "1",
            ],
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'confirm_password',
            'required' => true,
            'type' => 'Password',
            'options' => [
                'label' => 'Confirme a senha',
            ],
            'attributes' => [
                'required' => 'required',
                'data-parsley-equalto' => '#password'
            ],
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'password' => [
                'name'       => 'password',
                'required'   => true,
                'validators' => [
                    new StringLength(8),
                    new Regex('/[a-zA-z]/'),
                    new Regex('/[0-9]/'),
                    new Regex('/[.!@#$%^&*;:]/')
                ]
            ],
            'confirm_password' => [
                'name'       => 'confirm_password',
                'required'   => true,
                'validators' => [
                    [
                        'name'    => Identical::class,
                        'options' => [
                            'token' => 'password',
                        ],
                    ],
                ]
            ],
        ]));

        if($oldPassRequired) {
            $this->add([
                'name' => 'old_password',
                'required' => true,
                'type' => 'Password',
                'options' => [
                    'label' => 'Senha atual',
                ],
                'attributes' => [
                    'required' => 'required'
                ],
            ]);
        }
    }
}