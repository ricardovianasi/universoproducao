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
use Application\Entity\User\Category;
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
			'class' => 'user-form default-form-actions enable-validators form-reload',
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
                'help-block' => 'O nome pode ser de uma Pessoa Física ou Pessoa Jurídica'
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
				'id' => 'state',
                'class' => 'form-reload-trigger',
                //'data-form-reload-trigger' => ''
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
            'name' => 'instagram',
            'required' => false,
            'type' => 'text',
            'options' => [
                'label' => 'Instagram',
            ],
        ]);

        $this->add([
            'name' => 'occupation',
            'required' => false,
            'type' => 'text',
            'options' => [
                'label' => 'Cargo',
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
            'name' => 'phones',
            'options' => [
                'label' => 'Telefones',
            ],
        ]);

        $this->add([
            'name' => 'category',
            'type' => 'select',
            'options' => [
                'label' => 'Categoria',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateCategory()
            ],
            'attributes' => [
                'id' => 'category'
            ]
        ]);

        $this->add([
            'name' => 'subcategory',
            'type' => 'select',
            'options' => [
                'label' => 'Subcategoria',
                'empty_option' => 'Selecione',
                'value_options' => []
            ],
            'attributes' => [
                'id' => 'subcategory'
            ]
        ]);

        $this->add([
            'name' => 'status',
            'type' => 'select',
            'options' => [
                'label' => 'Ativo',
                'empty_option' => 'Selecione',
                'value_options' => [
                    1 => 'Sim',
                    0 => 'Não',
                ]
            ],
        ]);

        $this->add([
            'name' => 'tag',
            'type' => 'select',
            'options' => [
                'label' => 'Etiqueta',
                'empty_option' => 'Selecione',
                'value_options' => [
                    1 => 'Sim',
                    0 => 'Não',
                ]
            ],
        ]);

        $this->add([
            'name' => 'variable_field',
            'options' => [
                'label' => 'Campo variável',
            ],
        ]);

        $this->add([
            'name' => 'origin',
            'type' => 'select',
            'options' => [
                'label' => 'Tipo/Origem do cadastro',
                'empty_option' => 'Todos',
                'value_options' => [
                    'meuuniverso' => 'Meu Universo',
                    'contato' => 'SGC'
                ]
            ],
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
            ],
            [
                'name' => 'origin',
                'required'   => false,
                'allow_empty' => true
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
        ]));

        if($type == 'all' || $type == User::TYPE_PESSOA_FISICA || $type == User::TYPE_CADASTRO_INTERNACIONAL) {
            $this->add([
                'name' => 'birth_date',
                'options' => [
                    'label' => 'Data de Nascimento',
                ],
                'attributes' => [
                    'data-inputmask' => "'alias': 'dd/mm/yyyy', 'placeholder':'_'",
                    'required' => 'required'
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

    protected function findCities($stateId)
    {
        $cities = $this->getEntityManager()
            ->getRepository(City::class)
            ->findBy(['state'=>$stateId], ['name'=>'ASC']);

        $array = [];
        foreach($cities as $es) {
            $array[$es->getId()] = $es->getName();
        }

        return $array;
    }

	protected function populateCategory($parentId = null)
    {
        $categories = $this->entityManager
            ->getRepository(Category::class)
            ->findBy(['parent'=>$parentId], ['name'=>'asc']);

        $array = [];
        foreach($categories as $pr) {
            $array[$pr->getId()] = $pr->getName();
        }

        return $array;
    }

	public function setData($data)
	{
	    if(!empty($data['category'])) {
            $category = $data['category'];
            if(is_object($category) && method_exists($category, 'getId')) {
                $category = $category->getId();
            }

            //populate subcategory
            $this->get('subcategory')->setValueOptions($this->populateCategory($category));

            $data['category'] = $category;
        }

        if(!empty($data['subcategory'])) {
            $subcategory = $data['subcategory'];
            if(is_object($subcategory) && method_exists($subcategory, 'getId')) {
                $subcategory = $subcategory->getId();
            }
            $data['subcategory'] = $subcategory;
        }

	    if(!empty($data['state'])) {
	        $state = $data['state'];
	        if(is_object($state) && method_exists($state, 'getId')) {
	            $state = $state->getId();
            }

            $this->get('city')->setValueOptions($this->findCities($state));

	        $data['state'] = $state;
        }

	    if(!empty($data['city'])) {
	        $city = $data['city'];
            if(is_object($city) && method_exists($city, 'getId')) {
                $city = $city->getId();
            }
            $data['city'] = $city;
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