<?php
namespace Admin\Form\Event;

use Application\Entity\Event\EventType;
use Application\Entity\Event\Place;
use Application\Entity\Event\SubEvent;
use TwbBundle\Form\Element\Tinymce;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;


class EventForm extends Form
{
    protected $entityManager;

	public function __construct($entityManager=null)
	{
		parent::__construct('event-form');
		$this->setAttributes([
			'method', 'POST',
			'class' => 'form-horizontal'
		]);

		$this->entityManager = $entityManager;

		$this->add([
			'name' => 'full_name',
			'options' => [
				'label' => 'Nome do Completo',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
			]
		]);

		$this->add([
			'name' => 'short_name',
			'options' => [
				'label' => 'Nome Abreviado',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
			]
		]);

		$this->add([
			'name' => 'edition',
			'options' => [
				'label' => 'Número da Edição',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
			]
		]);

		$this->add([
			'name' => 'start_date',
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'options' => [
				'label' => 'Data de Início',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]

			],
			'attributtes' => [
				'readonly' => TRUE,
			]
		]);

		$this->add([
			'name' => 'end_date',
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'options' => [
				'label' => 'Data de Término',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
			],
			'attributtes' => [
				'readonly' => TRUE,
			]
		]);

		$this->add([
			'name' => 'type',
			'type' => 'select',
			'options' => [
				'label' => 'Tipo do Evento',
				'empty_option' => 'Selecione',
				'value_options' => EventType::toArray(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
			]

		]);

		$this->add([
			'name' => 'description',
			'type' => Tinymce::class,
			'options' => [
				'label' => 'Descrição',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
			],
			'attributtes' => [
				'id' => 'tinymce'
			]
		]);

        $this->add([
            'name' => 'places',
            'type' => 'select',
            'options' => [
                'label' => 'Locais de realização',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ],
                'value_options' => $this->populatePlaces()
            ],
            'attributes' => [
                'multiple' => 'multiple',
                'type'=>'select',
                'class' => 'form-control multi-select'
            ],
        ]);

        $this->add([
            'name' => 'sub_events',
            'type' => 'select',
            'options' => [
                'label' => 'Sub-mostras',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ],
                'value_options' => $this->populateSubEvents()
            ],
            'attributes' => [
                'multiple' => 'multiple',
                'type'=>'select',
                'class' => 'form-control multi-select'
            ],
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'sub_events' => [
                'name' => 'sub_events',
                'required'   => false,
                'allow_empty' => true
            ],
            'places' => [
                'name' => 'places',
                'required'   => false,
                'allow_empty' => true
            ],
        ]));
	}

	public function populatePlaces()
    {
        $options = [];

        if($this->getEntityManager()) {
            $places = $this
                ->getEntityManager()
                ->getRepository(Place::class)
                ->findAll();

            foreach ($places as $p) {
                if(!key_exists($p->getEventType(), $options)) {
                    $options[$p->getEventType()] = [
                        'label' => EventType::get($p->getEventType()),
                        'options' => []
                    ];
                }
                $options[$p->getEventType()]['options'][$p->getId()] = $p->getName();
            }
        }

        return $options;
    }

    public function populateSubEvents()
    {
        $options = [];

        if($this->getEntityManager()) {
            $subEvents = $this
                ->getEntityManager()
                ->getRepository(SubEvent::class)
                ->findAll();

            foreach ($subEvents as $p) {
                if(!key_exists($p->getType(), $options)) {
                    $options[$p->getType()] = [
                        'label' => EventType::get($p->getType()),
                        'options' => []
                    ];
                }
                $options[$p->getType()]['options'][$p->getId()] = $p->getName();
            }
        }

        return $options;
    }

    public function setData($data)
    {
        $places =[];
        if(!empty($data['places'])) {
            foreach ($data['places'] as $p) {
                if(is_object($p)) {
                    $places[] = $p->getId();
                } elseif (is_scalar($p)) {
                    $places[] = $p;
                }
            }
        }
        $data['places'] = $places;

        $subEvents =[];
        if(!empty($data['sub_events'])) {
            foreach ($data['sub_events'] as $s) {
                if(is_object($s)) {
                    $subEvents[] = $s->getId();
                } elseif (is_scalar($s)) {
                    $subEvents[] = $s;
                }
            }
        }
        $data['sub_events'] = $subEvents;

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

    /**
     * @return null
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param null $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}