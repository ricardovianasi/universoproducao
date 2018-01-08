<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 06/12/2017
 * Time: 09:47
 */

namespace Admin\Form\Programing;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Event\Place;
use Doctrine\ORM\EntityManager;
use TwbBundle\Form\Element\DatePicker;
use TwbBundle\Form\Element\DateTimePicker;
use Zend\Form\Form;

class ProgramingForm extends Form
{
    protected $entityManager;
    protected $event;

    public function __construct($em, $event=null)
    {
        if($em) {
            $this->entityManager = $em;
        }

        if($event) {
            if(is_numeric($event)) {
                $event = $this
                    ->entityManager
                    ->getRepository(Event::class)
                    ->find($event);
            }

            if(!$event instanceof Event) {
                throw new \InvalidArgumentException('Deve ser informado um id ou evento');
            }

            $this->event = $event;
        }

        parent::__construct('programing-form');
        $this->setAttributes([
           'class' => 'form-horizontal'
        ]);

        $this->add([
            'name' => 'date',
            'type' => DatePicker::class,
            'options' => [
                'label' => 'Data',
                'placeholder' => 'Data',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'data-required' => 'required',
                'placeholder' => 'Data',
                'data-inputmask' => "'alias': 'date'",
            ]
        ]);

        $this->add([
            'name' => 'start_time',
            'options' => [
                'label' => 'Hora de início',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'data-required' => 'required',
                'data-inputmask' => "'alias': 'hh:mm:ss'",
                'placeholder' => 'Hora início'
            ],
        ]);

        $this->add([
            'name' => 'end_time',
            'options' => [
                'label' => 'Hora de término',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'data-inputmask' => "'alias': 'hh:mm:ss'",
                'placeholder' => 'Hora término'
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'place',
            'options' => [
                'label' => 'Local',
                'empty_option' => 'Selecione',
                'value_options' => $this->populatePlace(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'data-required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'event',
            'options' => [
                'label' => 'Evento',
                'empty_option' => 'Selecione o evento',
                'value_options' => $this->populateEvents(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'data-label' => 'Evento',
                'class' => 'event-populate',
                'required' => 'required',
            ]

        ]);

        if($this->getEvent()) {
            $this->get('event')->setValue($this->getEvent()->getId());
        }
    }

    public function populatePlace()
    {
        $places = [];
        if($this->getEvent()) {
            foreach ($this->getEvent()->getPlaces() as $p) {
                $places[$p->getId()] = $p->getName();
            }
        } elseif($this->getEntityManager()) {
            $allPlaces = $this
                ->getEntityManager()
                ->getRepository(Place::class)
                ->findBy([], ['name'=>'ASC']);

            foreach ($allPlaces as $p) {
                if(!key_exists($p->getEventType(), $places)) {
                    $places[$p->getEventType()] = [
                        'label' => EventType::get($p->getEventType()),
                        'options' => []
                    ];
                }
                $places[$p->getEventType()]['options'][$p->getId()] = $p->getName();
            }
        }

        return $places;
    }

    public function populateEvents()
    {
        $options = [];

        if($this->getEntityManager()) {
            $events = $this
                ->getEntityManager()
                ->getRepository(Event::class)
                ->findBy([], ['startDate'=>'DESC']);

            foreach ($events as $p) {
                if(!key_exists($p->getType(), $options)) {
                    $options[$p->getType()] = [
                        'label' => EventType::get($p->getType()),
                        'options' => []
                    ];
                }
                $options[$p->getType()]['options'][$p->getId()] = $p->getShortName();
            }
        }

        return $options;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Event|null
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event|null $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function setData($data)
    {
        if(!empty($data['date'])) {
            $date = $data['date'];
            if($date instanceof \DateTime) {
                $data['date'] = $date->format('d/m/Y');
            }
        }

        if(!empty($data['start_time'])) {
            $startTime = $data['start_time'];
            if($startTime instanceof \DateTime) {
                $data['start_time'] = $startTime->format('H:i:s');
            }
        }

        if(!empty($data['end_time'])) {
            $endTime = $data['end_time'];
            if($endTime instanceof \DateTime) {
                $data['end_time'] = $endTime->format('H:i:s');
            }
        }

        if(!empty($data['place'])) {
            $place = $data['place'];
            if($place instanceof Place) {
                $data['place'] = $place->getId();
            }
        }

        if(!empty($data['event'])) {
            $event = $data['event'];
            if($event instanceof Event) {
                $data['event'] = $event->getId();
            }
        }

        return parent::setData($data);
    }

}