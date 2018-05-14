<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 17/12/2017
 * Time: 11:09
 */

namespace Admin\Form\Seminar;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Form\Form as EntityForm;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\Workshop\Workshop;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class SeminarSubscriptionForm extends Form
{
    private $entityManager;
    private $registration;
    private $event;

    public function __construct($em, $registration=null, $event=null)
    {
        if($em) {
            $this->entityManager = $em;
        }

        if($registration) {
            $this->registration = $registration;
        }

        if($event) {
            $this->event = $event;
        }

        parent::__construct('seminar-subscription-form');
        $this->setAttributes([
            'class' => 'form-horizontal',
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'user',
            'attributes' => [
                'class' => 'input-sm',
                'id' => 'user'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'user_search',
        ]);

        $this->add([
            'name' => 'id',
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'registration',
            'options' => [
                'label' => 'Regulamento',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateRegulations(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'id' => 'registration',
                'required' => true
            ]
        ]);

        $this->add([
            'name' => 'dateInit',
            'attributes' => [
                'placeholder' => 'De',
                'class' => 'input-sm',
                'data-inputmask' => "'alias': 'dd/mm/yyyy'"
            ]
        ]);

        $this->add([
            'name' => 'dateEnd',
            'attributes' => [
                'placeholder' => 'Até',
                'class' => 'input-sm',
                'data-inputmask' => "'alias': 'dd/mm/yyyy'"
            ]
        ]);

        $this->add([
            'name' => 'selected',
            'type' => 'hidden'
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'event',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => $this->populateEvents()
            ],
            'attributes' => [
                'data-label' => 'Evento',
                'id' => 'event'
            ]
        ]);

        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'event' => [
                'name' => 'event',
                'required'   => false,
                'allow_empty' => true
            ]
        ]));
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

    public function populateRegulations()
    {
        $regulations = [];
        if($this->getEntityManager()) {
            $coll = $this
                ->getEntityManager()
                ->getRepository(Registration::class)
                ->findBy([
                    'type' => Type::WORKSHOP
                ], ['startDate'=>'DESC']);

            foreach ($coll as $c) {
                $regulations[$c->getId()] = $c->getName();
            }
        }
        return $regulations;
    }

    public function setData($data)
    {
        if(!empty($data['user']) && is_object($data['user'])) {
            $user = $data['user'];
            $data['user'] = $user->getId();
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
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

    /**
     * @return Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param null $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    /**
     * @return null
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param null $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
}