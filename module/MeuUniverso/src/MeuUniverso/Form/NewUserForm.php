<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 10/09/2017
 * Time: 12:02
 */

namespace MeuUniverso\Form;

use Application\Entity\User\User;
use DoctrineModule\Validator\NoObjectExists;
use Util\Validator\Identifier;
use Zend\Form\Form;
use Zend\I18n\Filter\Alnum;
use Zend\Validator\EmailAddress;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Identical;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class NewUserForm extends Form
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct('user-form');
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'form-horizontal',
            'id' => 'post-form',
            'data-js-validate' => ''
        ]);

        $this->add([
            'name' => 'identifier',
            'type' => 'Text',
            'options' => [
                'label' => 'CPF, CNPJ ou Passaporte',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'name' => 'name',
            'required' => true,
            'options' => [
                'label' => 'Nome completo',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ],
                'help-block' => 'O nome pode ser de uma Pessoa Física ou Pessoa Jurídica'
            ],
            'attributes' => [
                'required' => 'required'
            ],
        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'type' => 'Email',
            'options' => [
                'label' => 'E-mail',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required'
            ],
        ]);

        $this->add([
            'name' => 'password',
            'required' => true,
            'type' => 'Password',
            'options' => [
                'label' => 'Senha',
                'twb-layout' => 'horizontal',
                'help-block' => 'Mínimo de 8 caracteres. Necessário incluir 1 número, 1 letra e 1 caractere especial',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
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
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'data-parsley-equalto' => '#password'
            ],
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'identifier' => [
                'name'       => 'identifier',
                'required'   => true,
                'validators' => [
                    new Identifier(),
                    [
                        'name' => NoObjectExists::class,
                        'options' => [
                            'object_repository' => $this->entityManager->getRepository(User::class),
                            'fields'            => 'identifier',
                            'messages' => [
                                'objectFound' => 'Este identificador já existe em nossa base de dados',
                            ],
                        ]
                    ]
                ],
                'filters' => [
                    new Alnum()
                ]
            ],
            'email' => [
                'name'       => 'email',
                'required'   => true,
                'validators' => [
                    new EmailAddress(),
                    [
                        'name' => NoObjectExists::class,
                        'options' => [
                            'object_repository' => $this->entityManager->getRepository(User::class),
                            'fields'            => 'email',
                            'messages' => [
                                'objectFound' => 'E-mail já existe em nossa base de dados',
                            ],
                        ]

                    ]
                ]
            ],
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
    }
}