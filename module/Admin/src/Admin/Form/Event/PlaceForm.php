<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/10/2017
 * Time: 11:16
 */

namespace Admin\Form\Event;

use Application\Entity\City;
use Application\Entity\Event\EventType;
use Application\Entity\State;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class PlaceForm extends Form
{
    private $entityManager;

    public function __construct($em=null)
    {
        parent::__construct('event-place-form');
        $this->setAttributes([
            'class' => 'form-horizontal default-form-actions enable-validators'
        ]);

        $this->entityManager = $em;

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Nome do local',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'event_type',
            'type' => 'select',
            'options' => [
                'label' => 'Tipo do evento',
                'empty_option' => 'Selecione',
                'value_options' => EventType::toArray(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required'
            ]

        ]);

        $this->add([
            'name' => 'description',
            'options' => [
                'label' => 'Descrição',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'id' => 'tinymce_minimal'
            ]
        ]);

        $this->add([
            'name' => 'state',
            'required' => true,
            'type' => 'select',
            'options' => [
                'label' => 'Estado',
                'empty_option' => 'Selecione',
                'value_options' => $this->findStates(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'state'
            ]
        ]);

        $this->add([
            'name' => 'city',
            'required' => true,
            'type' => 'select',
            'options' => [
                'label' => 'Cidade',
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'city'
            ]
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'name' => [
                'name' => 'name',
                'required' => true
            ],
        ]));
    }

    public function setData($data)
    {
        if(!empty($data['city'])) {

            if(is_object($data['city'])) {
                $city = $data['city'];
            } elseif(is_scalar($data['city'])) {
                $city = $this
                    ->getEntityManager()
                    ->getRepository(City::class)
                    ->find($data['city']);
            }

            $data['city'] = $city->getId();

            $stateId = $city->getState()->getId();
            if(empty($data['state'])) {
                $data['state'] = $stateId;
            }

            $cities = $this
                ->getEntityManager()
                ->getRepository(City::class)
                ->findBy(['state'=>$stateId], ['name'=>'ASC']);

            $citiesArray = [];
            foreach($cities as $c) {
                $citiesArray[$c->getId()] = $c->getName();
            }
            $this->get('city')->setValueOptions($citiesArray);
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

    protected function findStates()
    {
        $array = [];

        if($this->getEntityManager()) {
            $estados = $this->getEntityManager()
                ->getRepository(State::class)
                ->findBy([], ['name'=>'ASC']);

            foreach($estados as $es) {
                $array[$es->getId()] = $es->getName();
            }
        }

        return $array;
    }

    /**
     * @return \Doctrine\Orm\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function setEntityManager($em)
    {
        $this->entityManager = $em;
        return $this;
    }
}