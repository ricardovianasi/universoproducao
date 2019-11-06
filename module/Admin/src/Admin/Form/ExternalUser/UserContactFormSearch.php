<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/02/2016
 * Time: 12:07
 */

namespace Admin\Form\ExternalUser;

use Zend\InputFilter\Factory as InputFilterFactory;

class UserContactFormSearch extends UserForm
{
	public function __construct($em)
	{
        parent::__construct($em);
        $this->setAttribute('method', 'GET');
        $this->setAttribute('id', 'user-contact-form');

        $this->get('identifier')->setAttribute('required', '')->setOption('name', 'Nome');
        $this->get('name')->setAttribute('required', '');
        $this->get('email')->setAttribute('required', '');
        $this->get('state')->setAttribute('required', '');
        $this->get('city')->setAttribute('required', '');

        $this->add([
            'name' => 'selected',
            'type' => 'hidden'
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            [
                'name' => 'identifier',
                'required' => false,
            ],
            [
                'name' => 'email',
                'required' => false,
            ],
            [
                'name' => 'cep',
                'required' => false,
            ],
            [
                'name' => 'address',
                'required' => false
            ],
            [
                'name' => 'number',
                'required' => true
            ],
            [
                'name' => 'district',
                'required' => false
            ],
            [
                'name' => 'phones',
                'required' => false,
            ],
            'origin' => [
                'name' => 'origin',
                'required'   => false,
                'allow_empty' => true
            ],
            'status' => [
                'name' => 'status',
                'required'   => false,
                'allow_empty' => true
            ],
            'tag' => [
                'name' => 'tag',
                'required'   => false,
                'allow_empty' => true
            ],
            'category' => [
                'name' => 'category',
                'required'   => false,
                'allow_empty' => true
            ],
            'subcategory' => [
                'name' => 'subcategory',
                'required'   => false,
                'allow_empty' => true
            ],
            'city' => [
                'name' => 'city',
                'required'   => false,
                'allow_empty' => true
            ],
            'state' => [
                'name' => 'state',
                'required'   => false,
                'allow_empty' => true
            ],
        ]));
	}
}