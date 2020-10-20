<?php
namespace MeuUniverso\Form;

use Application\Entity\User\User;
use DoctrineModule\Validator\NoObjectExists;
use Util\Validator\Identifier;
use Zend\I18n\Filter\Alnum;
use Zend\Filter\StringTrim as StringTrim;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class ValidateUserForm extends \Admin\Form\ExternalUser\UserForm
{
    public function __construct($em)
    {
        parent::__construct($em);

        $this->setAttribute('data-js-validate', '');

        $this->add([
            'name' => 'password',
            'required' => true,
            'type' => 'Password',
            'options' => [
                'label' => 'Senha',
                'help-block' => 'Mínimo de 8 caracteres. Necessário incluir 1 número e 1 letra',
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'password',
                'data-parsley-minlength' => "8",
                'data-parsley-number' => "1",
                'data-parsley-special' => "1",
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
            'identifier' => [
                'name'       => 'identifier',
                'required'   => true,
                'validators' => [
                    new Identifier(),
                    [
                        'name' => NoObjectExists::class,
                        'options' => [
                            'object_repository' => $this->getEntityManager()->getRepository(User::class),
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
                            'object_repository' => $this->getEntityManager()->getRepository(User::class),
                            'fields'            => 'email',
                            'messages' => [
                                'objectFound' => 'E-mail já existe em nossa base de dados',
                            ],
                        ]

                    ]
                ],
                'filters' => [
                    new StringTrim()
                ]
            ],
            'password' => [
                'name'       => 'password',
                'required'   => true,
                'validators' => [
                    new StringLength(8),
                    new Regex('/[a-zA-z]/'),
                    new Regex('/[0-9]/')
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
            [
                'name' => 'gender',
                'required' => false,
                'allow_empty' => true,

            ],
            [
                'name' => 'status',
                'required'   => false,
                'allow_empty' => true
            ],
            [
                'name' => 'tag',
                'required'   => false,
                'allow_empty' => true
            ],
            [
                'name' => 'category',
                'required'   => false,
                'allow_empty' => true
            ],
            [
                'name' => 'subcategory',
                'required'   => false,
                'allow_empty' => true
            ],
            [
                'name' => 'origin',
                'required'   => false,
                'allow_empty' => true
            ],
            'phones' => [
                'name' => 'phones',
                'required' => true,
            ],
        ]));

    }
}