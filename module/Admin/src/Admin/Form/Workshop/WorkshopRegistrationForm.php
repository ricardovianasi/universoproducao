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
            'id' => 'submit_form'
        ]);

        $this->add([
            'name' => 'user',
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);

        $this->add([
            'name' => 'id',
            'attributes' => [
                'class' => 'input-sm',
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
                'class' => 'input-sm',
                'data-label' => 'Evento',
                'id' => 'event'
            ]
        ]);
        $this->add([
            'type' => 'Select',
            'name' => 'workshop',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => []
            ],
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'status',
            'options' => [
                'empty_option' => 'Selecione o status',
                'value_options' => Status::toArray()
            ],
            'attributes' => [
                'class' => 'input-sm',
                'data-label' => 'Status'
            ]
        ]);

        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([

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