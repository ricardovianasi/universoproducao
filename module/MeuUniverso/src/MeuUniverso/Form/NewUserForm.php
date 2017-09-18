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
use Zend\Validator\EmailAddress;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Identical;

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
                'label' => 'Nome',
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
//                'help-block' => 'Complexidade dee senha',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'password'
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
                    new NoObjectExists([
                        'object_repository' => $this->entityManager->getRepository(User::class),
                        'fields'            => 'identifier'
                    ])
                ]
            ],
            'email' => [
                'name'       => 'email',
                'required'   => true,
                'validators' => [
                    new EmailAddress(),
                    new NoObjectExists([
                        'object_repository' => $this->entityManager->getRepository(User::class),
                        'fields'            => 'email'
                    ])
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