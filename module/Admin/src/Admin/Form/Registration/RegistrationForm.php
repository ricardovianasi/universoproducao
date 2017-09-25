<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 23/09/2017
 * Time: 12:55
 */

namespace Admin\Form\Registration;

use Admin\Form\EntityManagerTrait;
Use Application\Entity\Event\Event;
use Application\Entity\Registration\Type;
use Zend\Form\Form;

class RegistrationForm extends Form
{
    use EntityManagerTrait;

    public function __construct($em=null, $type="")
    {
        $this->setEntityManager($em);

        parent::__construct('registration-form');
        $this->setAttributes([
            'class' => 'form-horizontal registration-form'
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Informe um nome',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'start_date',
            'options' => [
                'label' => 'Data de início',
                'placeholder' => 'Data'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'hour_start',
            'attributes' => [
                'class' => 'time-picker timepicker-24',
                'placeholder' => 'Hora',
                'required' => 'required',
            ],
        ]);

        $this->add([
            'name' => 'end_date',
            'options' => [
                'label' => 'Data de término',
                'placeholder' => 'Data'
            ],
        ]);
        $this->add([
            'name' => 'hour_end',
            'attributes' => [
                'class' => 'time-picker timepicker-24',
                'placeholder' => 'hora'
            ]
        ]);


        $this->add([
            'type' => 'select',
            'name' => 'type',
            'options' => [
                'label' => 'Selecione o tipo de inscrição',
                'value_options' => Type::toArray(),
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
            'name' => 'regulation',
            'type' => 'Textarea',
            'options' => [
                'label' => 'Regulamento',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'id' => 'tinymce_minimal'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    '1' => 'Habilitado',
                    '0' => 'Desabilitado'
                ],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-2',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'events',
            'options' => [
                'label' => 'Selecione o(s) eventos()',
                'value_options' => $this->populateEvents(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'multiple' => 'multiple',
                'type'=>'select',
                'class' => 'form-control multi-select',
                'required' => 'required'
            ],

        ]);

        $this->add([
            'type' => 'select',
            'name' => 'allow_international_registration',
            'options' => [
                'label' => 'Permitir cadastro de filme internacional',
                'value_options' => [
                    '0' => 'Não',
                    '1' => 'Sim',
                ],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-2',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'name' => 'allow_register_from',
            'type' => 'TwbBundle\Form\Element\DatePicker',
        ]);

        $this->add([
            'name' => 'allow_register_to',
            'type' => 'TwbBundle\Form\Element\DatePicker',
        ]);

        $this->add([
            'name' => 'edit_register_until',
            'options' => [
                'label' => 'Permitir edição do cadastro de filme até'
            ]
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'position',
            'options' => [
                'label' => 'Ordem de exibição',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-2',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'name' => 'info',
            'type' => 'textarea',
            'options' => [
                'label' => 'Informações adicionais',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'name' => 'cover',
            'type' => 'hidden',
            'attributes' => [
                'placeholder' => 'Informe uma descrição',
                'id' => 'file'
            ],
            'options' => [
                'label' => 'Imagem'
            ]
        ]);
    }

    public function setData($data)
    {
        $events = [];
        if(!empty($data['events'])) {
            foreach($data['events'] as $e) {
                if(is_object($e)) {
                    $events[] = $e->getId();
                } elseif (is_scalar($e)) {
                    $events[] = $e;
                }
            }
        }
        $data['events'] = $events;

        if(!empty($data['start_date'])) {
            if($data['start_date'] instanceof \DateTime) {
                $startDate = $data['start_date'];
                $data['start_date'] = $startDate->format('d/m/Y');
                $data['hour_start'] = $startDate->format('H:i');
            }
        }

        if(!empty($data['end_date'])) {
            if($data['end_date'] instanceof \DateTime) {
                $endDate = $data['end_date'];
                $data['end_date'] = $endDate->format('d/m/Y');
                $data['hour_end'] = $endDate->format('H:i');
            }
        }

        if(!empty($data['allow_register_from'])) {
            if ($data['allow_register_from'] instanceof \DateTime) {
                $data['allow_register_from'] = ($data['allow_register_from'])->format('d/m/Y');
            }
        }

        if(!empty($data['allow_register_to'])) {
            if ($data['allow_register_to'] instanceof \DateTime) {
                $data['allow_register_to'] = ($data['allow_register_to'])->format('d/m/Y');
            }
        }

        if(!empty($data['edit_register_until'])) {
            if ($data['edit_register_until'] instanceof \DateTime) {
                $data['edit_register_until'] = ($data['edit_register_until'])->format('d/m/Y');
            }
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

    public function populateEvents()
    {
        $options = [];
        if ($this->getEntityManager()) {
            $events = $this->getEntityManager()->getRepository(Event::class)->findBy([], [
                'startDate' => 'DESC'
            ]);
            foreach ($events as $e) {
                $options[$e->getId()] = $e->getShortName();
            }
        }
        return $options;
    }
}