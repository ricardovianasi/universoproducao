<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 17/12/2017
 * Time: 11:09
 */

namespace Admin\Form\Workshop;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Form\Form as EntityForm;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\Workshop\PontuationItems;
use Application\Entity\Workshop\Workshop;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Between;

class WorkshopRegistrationForm extends Form
{
    private $entityManager;
    private $registration;

    public function __construct($em, $registration=null)
    {
        if($em) {
            $this->entityManager = $em;
        }

        if($registration) {
            $this->registration = $registration;
        }

        parent::__construct('workshop-registration-form');
        $this->setAttributes([
            'class' => 'form-horizontal movie-form',
            'id' => 'workshop-registration-form'
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
        $this->add([
            'type' => 'Select',
            'name' => 'workshop',
            'options' => [
                'label' => 'Oficina',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateWorkshops(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'empty_option' => 'Selecione o status',
                'value_options' => Status::toArray()
            ],
            'attributes' => [
                'data-label' => 'Status'
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

        $subForm = new Fieldset('form_answer');

        if($this->getRegistration()) {
            if($formOption = $this->getRegistration()->getOption(Options::WORKSHOP_FORM)) {
                /** @var EntityForm $form */
                $form = $this
                    ->getEntityManager()
                    ->getRepository(EntityForm::class)
                    ->find($formOption->getValue());

                if($form) {

                    foreach ($form->getElements() as $el) {
                        $options = [];
                        if($elementOptions = $el->getOptions()) {
                            $options = json_decode($elementOptions, true);
                        }
                        $options['label'] = $el->getLabel();
                        $options['twb-layout'] = 'horizontal';
                        $options['column-size'] = 'md-6';
                        $options['label_attributes'] = [
                            'class' => 'col-md-4'
                        ];

                        $attributes = [];
                        if($elementAttributes = $el->getAttributes()) {
                            $attributes = json_decode($elementAttributes, true);
                        }

                        if($el->getRequired()) {
                            $attributes['required'] = 'required';
                            $attributes['rows'] = 8;
                        }

                        $subForm->add([
                            'name' => $el->getName(),
                            'type' => $el->getType(),
                            'options' => $options,
                            'attributes' => $attributes
                        ]);
                    }
                }
            }
        }

        $this->add($subForm);
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

    public function populateWorkshops()
    {
        $options = [];
        if($this->getEntityManager() && $this->getRegistration()) {
            $items = $this
                ->getEntityManager()
                ->getRepository(Workshop::class)
                ->findBy([
                    'registration' => $this->getRegistration()->getId()
                ]);

            foreach ($items as $c) {
                $options[$c->getId()] = $c->getName();
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
        if(!empty($data['form_answers'])) {
            foreach ($data['form_answers'] as $answer) {
                if(is_object($answer)) {
                    $data['form_answer'][$answer->getQuestion()] = $answer->getAnswer();
                }
            }
        }

        if(!empty($data['user']) && is_object($data['user'])) {
            $user = $data['user'];
            $data['user'] = $user->getId();
        }

        if(!empty($data['workshop']) && is_object($data['workshop'])) {
            $workshop = $data['workshop'];
            $data['workshop'] = $workshop->getId();
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
}