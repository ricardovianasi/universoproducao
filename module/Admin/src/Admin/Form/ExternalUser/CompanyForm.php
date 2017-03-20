<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 16/03/2017
 * Time: 21:32
 */

namespace Admin\Form\ExternalUser;

use Admin\Form\EntityManagerTrait;
use Application\Entity\State;
use Zend\Form\Form;
use Zend\Validator\EmailAddress;

class CompanyForm extends Form
{
    use EntityManagerTrait;

    public function __construct($em=null)
    {
        if($em) {
            $this->entityManager = $em;
        }

        parent::__construct('company');

        $this->add([
            'name' => 'cnpj',
            'type' => 'TwbBundle\Form\Element\Cnpj',
            'options' => [
                'label' => 'CNPJ',
            ],
            'attributes' => [
                'required' => 'required'
            ],
        ]);

        $this->add([
            'name' => 'name',
            'required' => true,
            'options' => [
                'label' => 'Nome',
            ],
            'attributes' => [
                'required' => 'required'
            ],

        ]);

        $this->add([
            'name' => 'social_reason',
            'required' => true,
            'options' => [
                'label' => 'Razão Social',
            ],
            'attributes' => [
                'required' => 'required'
            ],

        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'type' => 'text',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'required' => 'required'
            ],
            'validators'=>array(
                new EmailAddress()
            ),
        ]);

        $this->add([
            'name' => 'site',
            'required' => false,
            'type' => 'text',
            'options' => [
                'label' => 'Site',
            ]
        ]);

        $this->add([
            'name' => 'cep',
            'required' => true,
            'type' => 'text',
            'options' => [
                'label' => 'CEP',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'address',
            'required' => true,
            'type' => 'text',
            'options' => [
                'label' => 'Rua',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'number',
            'required' => true,
            'type' => 'text',
            'options' => [
                'label' => 'Número',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'district',
            'required' => true,
            'type' => 'text',
            'options' => [
                'label' => 'Bairro',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'complement',
            'required' => false,
            'type' => 'text',
            'options' => [
                'label' => 'Complemento',
            ]
        ]);

        $this->add([
            'name' => 'state',
            'required' => true,
            'type' => 'select',
            'options' => [
                'label' => 'Estado',
                'empty_option' => 'Selecione',
                'value_options' => $this->findStates()
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'state'
            ]
        ]);

        $this->add([
            'name' => 'city',
            'required' => true,
            'type' => 'select',
            'options' => [
                'label' => 'Cidade',
                'empty_option' => 'Selecione'
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'city'
            ]
        ]);
    }

    protected function findStates()
    {
        $estados = $this->getEntityManager()
            ->getRepository(State::class)
            ->findBy([], ['name'=>'ASC']);

        $array = [];
        foreach($estados as $es) {
            $array[$es->getId()] = $es->getName();
        }

        return $array;
    }

}