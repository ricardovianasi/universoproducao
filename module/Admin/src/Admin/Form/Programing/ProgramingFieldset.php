<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 15:01
 */

namespace Admin\Form\Programing;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Event\Place;
use Application\Entity\Programing\Meta;
use TwbBundle\Form\Element\DatePicker;
use Zend\Form\Fieldset;

class ProgramingFieldset extends Fieldset
{
    private $entityManager;
    private $event;

    public function __construct($name = null, array $options = [], $entityManager = null)
    {
        parent::__construct($name, $options);

        if($entityManager) {
            $this->entityManager = $entityManager;
        }

        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'date',
            'type' => DatePicker::class,
            'options' => [
                'label' => 'Data',
                'placeholder' => 'Data',
            ],
            'attributes' => [
                'required' => 'required',
                'class' => 'programming_date',
                'placeholder' => 'Data',
                'data-inputmask' => "'alias': 'date'",
            ]
        ]);

        $this->add([
            'name' => 'start_time',
            'options' => [
                'label' => 'Hora de início',
            ],
            'attributes' => [
                'required' => 'required',
                'class' => 'programming_start_time',
                'data-inputmask' => "'alias': 'hh:mm:ss'",
                'placeholder' => 'Hora início'
            ],
        ]);

        $this->add([
            'name' => 'end_time',
            'options' => [
                'label' => 'Hora de término',
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
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'available_places',
            'options' => [
                'label' => 'Vagas',
            ],
        ]);

        $this->add([
            'name' => Meta::ADDITIONAL_INFO,
            'type' => 'Textarea',
            'options' => [
                'label' => 'Informações adicionais',
            ]
        ]);
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
}