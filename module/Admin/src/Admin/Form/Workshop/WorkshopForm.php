<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/10/2017
 * Time: 16:48
 */

namespace Admin\Form\Workshop;


use Zend\Form\Form;

class WorkshopForm extends Form
{
    private $entityManager;

    public function __construct($em=null)
    {
        parent::__construct('workshop-manager-form');
        $this->setAttributes([
            'class' => 'default-form-actions enable-validators'
        ]);

        $this->entityManager = $em;

        $this->add([
            'name' => 'registration',
            'required' => true,
            'type' => 'select',
            'options' => [
                'label' => 'Regulamento',
                'empty_option' => 'Selecione',
                'value_options' => [],
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome',
            ],
            'attributes' => [
                'placeholder' => 'Nome da oficina',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'manager',
            'required' => true,
            'type' => 'select',
            'options' => [
                'label' => 'Responsável',
                'empty_option' => 'Selecione',
                'value_options' => [],
            ],
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'minimum_age',
            'options' => [
                'label' => 'Idade mínima',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'maximum_age',
            'options' => [
                'label' => 'Idade máxima',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'duration',
            'options' => [
                'label' => 'Carga horária',
            ],
            'attributes' => [
                'required' => 'required',
                'data-inputmask' => "'alias': 'hh:mm:ss', 'placeholder':'_'"
            ]
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'available_subscriptions',
            'options' => [
                'label' => 'Vagas',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'maximum_subscriptions',
            'options' => [
                'label' => 'Máximo de inscrições permitido',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'description',
            'options' => [
                'label' => 'Descrição',
            ],
            'attributes' => [
                'class' => 'tinymce_minimal'
            ]
        ]);

        $this->add([
            'name' => 'requirements',
            'options' => [
                'label' => 'Pré-requisitos',
            ],
            'attributes' => [
                'class' => 'tinymce_minimal'
            ]
        ]);
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