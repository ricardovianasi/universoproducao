<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 16/03/2017
 * Time: 21:32
 */

namespace Admin\Form\ExternalUser;

use Zend\Form\Form;

class CompanyForm extends Form
{
    public function __construct()
    {
        parent::__construct('company');

        $this->add([
            'name' => 'company[cnpj]',
            'type' => 'TwbBundle\Form\Element\Cnpj',
            'options' => [
                'label' => 'CNPJ',
            ],
            'attributes' => [
                'required' => 'required'
            ],
        ]);

        $this->add([
            'name' => 'company[name]',
            'required' => true,
            'options' => [
                'label' => 'Nome',
            ],
            'attributes' => [
                'required' => 'required'
            ],

        ]);

        $this->add([
            'name' => 'company[social_reason]',
            'required' => true,
            'options' => [
                'label' => 'Razão Social',
            ],
            'attributes' => [
                'required' => 'required'
            ],

        ]);
    }

}