<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 05/04/2018
 * Time: 13:01
 */

namespace Admin\Form\Project;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Registration\Status;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class ProjectSearchForm extends Form
{
    protected $entityManager;

    public function __construct($entityManager=null)
    {
        if($entityManager) {
            $this->entityManager = $entityManager;
        }

        parent::__construct('project-search-form');
        $this->setAttributes([
            'id' => 'project-search-form',
            'method' => 'get'
        ]);

        $this->add([
            'name' => 'id',
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título original'
            ],
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);

        $this->add([
            'name' => 'user',
            'options' => [
                'label' => 'Usuário'
            ],
            'attributes' => [
                'class' => 'input-sm',
            ]
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
                'class' => 'input-sm',
                'id' => 'event'
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

    public function getEntityManager()
    {
        return $this->entityManager;
    }

}