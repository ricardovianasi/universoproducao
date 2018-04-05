<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 05/04/2018
 * Time: 13:01
 */

namespace Admin\Form\Project;

use Application\Entity\Event\Event;
use Application\Entity\Registration\Status;
use Zend\Form\Form;

class ProjectSearchForm extends Form
{
    protected $entityManager;

    public function __construct($entityManager=null)
    {
        parent::__construct('project-search-form');

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título original'
            ],
        ]);

        $this->add([
            'name' => 'user',
            'options' => [
                'label' => 'Usuário'
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'event',
            'options' => [
                'empty_option' => 'Selecione o evento',
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