<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/03/2018
 * Time: 09:39
 */
namespace Admin\Form\EducationalProject;

use Admin\Form\EntityManagerTrait;
use Admin\Form\Project\FileFieldset;
use Application\Entity\EducationalProject\Category;
use Application\Entity\Registration\Registration;
use Application\Entity\State;
use Doctrine\ORM\EntityManager;
use Psr\Log\InvalidArgumentException;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class EducationalProjectForm extends Form
{
    use EntityManagerTrait;

    protected $registration;

    public function __construct(EntityManager $em, $registration=null, $disableValidations=false)
    {
        parent::__construct('educational-project-form');
        $this->setAttributes([
            'id' => 'submit_form',
            'class' => 'form-horizontal educational-project-form efault-form-actions enable-validators'
        ]);

        if(!$em) {
            throw new InvalidArgumentException('The entity manager argument is necessary!');
        }
        $this->setEntityManager($em);

        if($registration) {
            if($registration instanceof Registration) {
                $this->setRegistration($registration);
            } elseif(is_numeric($registration)) {
                $this->registration = $this->getRepository(Registration::class)->find($registration);
            } else {
                $this->registration = $this->getRepository(Registration::class)->findOneBy(['hash'=>$registration]);
            }
        }

        $this->add([
            'type' => 'Textarea',
            'name' => 'proposers_name',
            'options' => [
                'label' => 'Nome do(s) Proponente(s)',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'responsible',
            'options' => [
                'label' => 'Nome de um proponente que representará o projeto',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'institution',
            'options' => [
                'label' => 'Instituição',
                'help-block' => 'Onde trabalha ou foi formado',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'institution_address',
            'options' => [
                'label' => 'Endereço da Instituição',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
        ]);


        $this->add([
            'type' => 'Select',
            'name' => 'institution_uf',
            'options' => [
                'label' => 'UF da Instituição:',
                'value_options' => $this->populateStates(),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'institution_phone',
            'options' => [
                'label' => 'Telefone fixo da Instituição',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
        ]);

        $this->add([
            'name' => 'institution_mobile_phone',
            'options' => [
                'label' => 'Telefone Celular de contato',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'institution_email',
            'options' => [
                'label' => 'Email de contato',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título do projeto',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'category',
            'options' => [
                'label' => 'Categoria',
                'value_options' => $this->populateCategory(),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'help-block' => 'Consulte o regulamento'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'presentation',
            'options' => [
                'label' => 'Apresentação do projeto',
                'help-block' => 'Limite de 500 caracteres, incluindo espaços',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
            ],
            'attributes' => [
                'rows' => '6',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'curriculum',
            'options' => [
                'label' => 'Curriculum Vitae do proponente do projeto',
                'help-block' => 'Limite de 1.500 caracteres, incluindo espaços',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],

            ],
            'attributes' => [
                'rows' => '10',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'complete_text',
            'type' => 'Textarea',
            'options' => [
                'label' => 'Texto completo',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'help-block' => 'Histórico e justificativa, bases teórico-metodológicas utilizadas, principais 
                ações e reflexões junto a comunidade, duas questões sobre a categoria escolhida para dinamizar 
                a interlocução do trabalho com os participantes do seminário. <br />Limite de 4.000 caracteres, 
                incluindo espaços.',
            ],
            'attributes' => [
                'rows' => '15',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'references',
            'type' => 'Textarea',
            'options' => [
                'label' => 'Referências',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'help-block' => 'Limite de 1.000 caracteres, incluindo espaços',
            ],
            'attributes' => [
                'rows' => '10',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'links',
            'type' => 'Textarea',
            'options' => [
                'label' => 'Links de materiais audiovisuais citados e realizados pelo projeto',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'help-block' => 'Máximo de 10 minutos de duração',
            ],
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'files',
            'options' => [
                'count' => 2,
                'should_create_template' => false,
                'target_element' => [
                    'type' => FileFieldset::class
                ]
            ]

        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'proposers_name' => [
                'name'       => 'proposers_name',
                'required'   => true,
            ],
            'responsible' => [
                'name'       => 'responsible',
                'required'   => true,
            ],
            'institution' => [
                'name'       => 'institution',
                'required'   => true,
            ],
            'institution_uf' => [
                'name'       => 'institution_uf',
                'required'   => true,
            ],
            'institution_mobile_phone' => [
                'name'       => 'institution_mobile_phone',
                'required'   => true,
            ],
            'institution_email' => [
                'name'       => 'institution_email',
                'required'   => true,
            ],
            'title' => [
                'name'       => 'title',
                'required'   => true,
            ],
            'category' => [
                'name'       => 'category',
                'required'   => true,
            ],
            'presentation' => [
                'name'       => 'presentation',
                'required'   => true,
                'validators' => [
                    [
                        'name' => 'string_length',
                        'options' => [
                            'max' => 500
                        ]
                    ]
                ]
            ],
            'curriculum' => [
                'name'       => 'curriculum',
                'required'   => true,
                'validators' => [
                    [
                        'name' => 'string_length',
                        'options' => [
                            'max' => 1500
                        ]
                    ]
                ]
            ],
            'complete_text' => [
                'name'       => 'complete_text',
                'required'   => true,
                'validators' => [
                    [
                        'name' => 'string_length',
                        'options' => [
                            'max' => 4000
                        ]
                    ]
                ]
            ]

        ]));
    }

    /**
     * @return Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param mixed $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    public function populateStates()
    {
        $states = [];
        $list = $this->getEntityManager()->getRepository(State::class)->findBy([], ['name'=>'ASC']);

        foreach ($list as $l) {
            $states[$l->getName()] = $l->getName();
        }

        return $states;
    }

    public function populateCategory()
    {
        $coll = [];
        if($this->getRegistration()) {
            $items = $this
                ->getRepository(Category::class)
                ->findBy(['registration'=>$this->getRegistration()->getId()],['name'=>'ASC']);
        } else {
            $items = $this
                ->getRepository(Category::class)
                ->findBy([],['name'=>'ASC']);
        }
        foreach ($items as $i) {
            $coll[$i->getId()] = $i->getName();
        }
        return $coll;
    }

    public function setData($data)
    {
        if(!empty($data['category']) && $data['category'] instanceof Category) {
            $cat = $data['category'];
            $data['category'] = $cat->getId();
        }
        return parent::setData($data); // TODO: Change the autogenerated stub
    }
}