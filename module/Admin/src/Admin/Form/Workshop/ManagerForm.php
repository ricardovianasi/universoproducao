<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/10/2017
 * Time: 11:16
 */

namespace Admin\Form\Workshop;

use Application\Entity\City;
use Application\Entity\Event\EventType;
use Application\Entity\State;
use Util\Validator\Cpf;
use Zend\Form\Form;
use Zend\I18n\Filter\Alnum;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Date;
use Zend\Validator\EmailAddress;

class ManagerForm extends Form
{
    private $entityManager;

    public function __construct($em=null)
    {
        parent::__construct('workshop-manager-form');
        $this->setAttributes([
            'class' => 'default-form-actions enable-validators'
        ]);

        $this->entityManager = $em;

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome',
            ],
            'attributes' => [
                'placeholder' => 'Nome do resposável',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'cpf',
            'options' => [
                'label' => 'CPF',
            ],
            'attributes' => [
                'data-inputmask' => "'mask': '999.999.999-99'",
            ]
        ]);

        $this->add([
            'name' => 'company',
            'options' => [
                'label' => 'Empresa',
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'address',
            'options' => [
                'label' => 'Endereço',
            ],
        ]);

        $this->add([
            'name' => 'state',
            'required' => true,
            'type' => 'select',
            'options' => [
                'label' => 'Estado',
                'empty_option' => 'Selecione',
                'value_options' => $this->findStates(),
            ],
            'attributes' => [
                'id' => 'state'
            ]
        ]);

        $this->add([
            'name' => 'city',
            'required' => true,
            'type' => 'select',
            'options' => [
                'label' => 'Cidade',
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'id' => 'city'
            ]
        ]);

        $this->add([
            'name' => 'phone',
            'options' => [
                'label' => 'Telefone',
            ],
        ]);

        $this->add([
            'name' => 'mobile_phone',
            'options' => [
                'label' => 'Telefone celular',
            ],
        ]);

        $this->add([
            'name' => 'site',
            'options' => [
                'label' => 'Site',
            ],
            'attributes' => [
                'placeholder' => 'http://',
            ]
        ]);

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'E-mail',
            ]
        ]);

        $this->add([
            'name' => 'birth_date',
            'options' => [
                'label' => 'Data de Nascimento',
            ],
            'attributes' => [
                'data-inputmask' => "'alias': 'dd/mm/yyyy', 'placeholder':'_'",
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'gender',
            'required' => false,
            'allow_empty' => true,
            'options' => [
                'label' => 'Sexo',
                'empty_option' => 'Selecione',
                'value_options' => [
                    'm' => 'Masculino',
                    'f' => 'Feminino'
                ],
            ]
        ]);

        $this->add([
            'name' => 'description',
            'options' => [
                'label' => 'Descrição',
            ],
            'attributes' => [
                'class' => 'tinymce_minimal'
            ]
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'name' => [
                'name' => 'name',
                'required' => true
            ],
            [
                'name' => 'gender',
                'required' => false,
                'allow_empty' => true,
            ],
            [
                'name' => 'state',
                'required' => false,
                'allow_empty' => true,
            ],
            [
                'name' => 'city',
                'required' => false,
                'allow_empty' => true,
            ],
            [
                'name' => 'birth_date',
                'required' => false,
                'validators' => [
                    new Date(['format'=>'d/m/Y'])
                ]
            ],
            [
                'name' => 'email',
                'required' => false,
                'validators' => [
                    new EmailAddress()
                ]
            ],
            [
                'name' => 'cpf',
                'required' => false,
                'validators' => [
                    new Cpf()
                ],
                'filters' => [
                    new Alnum()
                ]
            ]
        ]));
    }

    public function setData($data)
    {
        if(!empty($data['city'])) {

            if(is_object($data['city'])) {
                $city = $data['city'];
            } elseif(is_scalar($data['city'])) {
                $city = $this
                    ->getEntityManager()
                    ->getRepository(City::class)
                    ->find($data['city']);
            }

            $data['city'] = $city->getId();

            $stateId = $city->getState()->getId();
            if(empty($data['state'])) {
                $data['state'] = $stateId;
            }

            $cities = $this
                ->getEntityManager()
                ->getRepository(City::class)
                ->findBy(['state'=>$stateId], ['name'=>'ASC']);

            $citiesArray = [];
            foreach($cities as $c) {
                $citiesArray[$c->getId()] = $c->getName();
            }
            $this->get('city')->setValueOptions($citiesArray);
        }

        if(!empty($data['birth_date'])) {
            if($data['birth_date'] instanceof \DateTime) {
                $birth_date = $data['birth_date'];
                $data['birth_date'] = $birth_date->format('d/m/Y');
            }
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

    protected function findStates()
    {
        $array = [];

        if($this->getEntityManager()) {
            $estados = $this->getEntityManager()
                ->getRepository(State::class)
                ->findBy([], ['name'=>'ASC']);

            foreach($estados as $es) {
                $array[$es->getId()] = $es->getName();
            }
        }

        return $array;
    }

    /**
     * @return \Doctrine\Orm\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function setEntityManager($em)
    {
        $this->entityManager = $em;
        return $this;
    }
}