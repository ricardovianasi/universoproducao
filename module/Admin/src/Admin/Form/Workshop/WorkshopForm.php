<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/10/2017
 * Time: 16:48
 */

namespace Admin\Form\Workshop;

use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Type;
use Application\Entity\Workshop\Manager;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

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
            'type' => 'Select',
            'name' => 'registration',
            'options' => [
                'label' => 'Regulamento',
                'value_options' => $this->populateRegulations(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
                'class' => 'select2'
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
                'value_options' => $this->populateManagers(),
            ],
            'attributes' => [
                'required' => 'required',
                'class' => 'select2'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'minimum_age',
            'options' => [
                'label' => 'Idade mínima',
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'maximum_age',
            'options' => [
                'label' => 'Idade máxima',
            ]
        ]);

        $this->add([
            'name' => 'duration',
            'options' => [
                'label' => 'Carga horária',
            ],
            'attributes' => [
                'required' => 'required'
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

        $this->add([
            'name' => 'file',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'file'
            ],
            'options' => [
                'label' => 'Imagem'
            ]
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'maximum_age' => [
                'name' => 'maximum_age',
                'required'   => false
            ],
            'minimum_age' => [
                'name' => 'minimum_age',
                'required'   => false
            ]
        ]));

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

    public function populateManagers()
    {
        $managers = [];
        if($this->getEntityManager()) {
            $coll = $this
                ->getEntityManager()
                ->getRepository(Manager::class)
                ->findBy([], ['name'=>'ASC']);

            foreach ($coll as $c) {
                $managers[$c->getId()] = $c->getName();
            }
        }
        return $managers;
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

    public function setData($data)
    {
        if(!empty($data['manager'])) {
            $manager = $data['manager'];
            if(is_object($manager)) {
                $data['manager'] = $manager->getId();
            }
        }

        if(!empty($data['registration'])) {
            $registration = $data['registration'];
            if(is_object($registration)) {
                $data['registration'] = $registration->getId();
                $data['event'] = $registration->geEvent()->getId();
            }
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }
}