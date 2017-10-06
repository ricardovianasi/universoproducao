<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 07/01/2016
 * Time: 15:09
 */

namespace Admin\Form\ExternalUser;

use Application\Entity\City;
use Application\Entity\State;
use Application\Entity\User\User;
use Util\Validator\Identifier;
use Zend\Form\Form;
use Zend\I18n\Filter\Alnum;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Date;
use Zend\Validator\EmailAddress;

class UserForm extends Form
{
	private $entityManager;

	public function __construct($em, $type='all')
	{
		parent::__construct('user-form');
		$this->setAttributes([
			'method' => 'POST',
			'class' => 'user-form default-form-actions enable-validators',
			'id' => 'post-form'
		]);

		$this->entityManager = $em;

		$this->add([
			'name' => 'identifier',
			'type' => 'Text',
			'options' => [
				'label' => 'CPF, CNPJ ou Passaporte',
			],
			'attributes' => [
				'required' => 'required'
			],
		]);

		$this->add([
			'name' => 'name',
			'required' => true,
			'options' => [
				'label' => 'Nome completo',
                'help-block' => 'Pessoa física ou jurídica'
			],
			'attributes' => [
				'required' => 'required'
			],

		]);

		$this->add([
			'name' => 'alias',
			'type' => 'text',
			'required' => true,
			'type' => 'text',
			'options' => [
				'label' => 'Como gostaria de ser chamado(apelido)',
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

		$this->add([
			'name' => 'facebook',
			'required' => false,
			'type' => 'text',
			'options' => [
				'label' => 'Facebook',
			],
		]);

		$this->add([
			'name' => 'twitter',
			'required' => false,
			'type' => 'text',
			'options' => [
				'label' => 'Twitter',
			],
		]);

		$this->add([
		    'type' => 'hidden',
            'name' => 'phones'
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'dependents'
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            [
                'name' => 'identifier',
                'required' => true,
                'validators' => [
                    new Identifier()
                ],
                'filters' => [
                    new Alnum()
                ]

            ],
            [
                'name' => 'email',
                'required' => true,
                'validators' => [
                    ['name' => 'email_address']
                ]
            ],
            [
                'name' => 'cep',
                'required' => true,
            ],
            [
                'name' => 'address',
                'required' => true
            ],
            [
                'name' => 'number',
                'required' => true
            ],
            [
                'name' => 'district',
                'required' => true
            ],
            [
                'name' => 'phones',
                'required' => true,
            ]
        ]));

        if($type == 'all' || $type == User::TYPE_PESSOA_FISICA || $type == User::TYPE_CADASTRO_INTERNACIONAL) {
            $this->add([
                'name' => 'birth_date',
                'options' => [
                    'label' => 'Data de Nascimento',
                ],
                'attributes' => [
                    'data-inputmask' => "'alias': 'dd/mm/yyyy', 'placeholder':'_'",
                    'required' => true
                ]
            ]);
            $this->getInputFilter()->add([
                'name' => 'birth_date',
                'required' => true,
                'validators' => [
                    new Date(['format'=>'d/m/Y'])
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
                    ]
                ]
            ]);
            $this->getInputFilter()
                ->add([
                    'name' => 'gender',
                    'required' => false,
                    'allow_empty' => true,

                ]);
        }

        if($type == User::TYPE_CADASTRO_INTERNACIONAL || $type == 'all') {
            //Campo de país aberto e campo de estado aberto...
        }
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

	public function setData($data)
	{
		if(!empty($data['city'])) {

			if(is_object($data['city'])) {
				$city = $data['city'];
			} elseif(is_scalar($data['city'])) {
				$city = $this->getEntityManager()->getRepository(City::class)->find($data['city']);
			}

			$data['city'] = $city->getId();

			$stateId = $city->getState()->getId();
			if(empty($data['state'])) {
				$data['state'] = $stateId;
			}

			$cities = $this->getEntityManager()
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