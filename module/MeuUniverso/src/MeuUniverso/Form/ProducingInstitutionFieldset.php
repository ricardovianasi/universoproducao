<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 15/03/2019
 * Time: 08:47
 */

namespace MeuUniverso\Form;


class ProducingInstitutionFieldset extends \Admin\Form\Movie\ProducingInstitutionFieldset
{

    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->get('name')->setAttribute('required', 'required');
        $this->get('address')->setAttribute('required', 'required');
        $this->get('country')->setAttribute('required', 'required');
        $this->get('uf')->setAttribute('required', 'required');
        $this->get('phone')->setAttribute('required', 'required');
        $this->get('email')->setAttribute('required', 'required');
    }

    public function getInputFilterSpecification()
    {
        return [
            'name' => [
                'name' => 'name',
                'required' => true,
            ],
            'address' => [
                'name' => 'address',
                'required' => true,
            ],
            'uf' => [
                'name' => 'uf',
                'required' => true,
            ],
            'phone' => [
                'name' => 'phone',
                'required' => true,
            ],
            'email' => [
                'name' => 'email',
                'required' => true,
            ],
        ];

    }
}