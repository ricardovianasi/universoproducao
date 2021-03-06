<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 13/12/2017
 * Time: 09:17
 */

namespace Admin\Form\Movie;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Registration\Status;
use Zend\Form\Form;

class MovieStatusModalForm extends Form
{
    protected $entityManager;

    public function __construct($em)
    {
        $this->entityManager = $em;

        parent::__construct();
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'teste'
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'status',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => Status::toArray(),
                'label' => 'Status',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'event',
            'options' => [
                'label' => 'Evento',
                'empty_option' => 'Selecione o evento',
                'value_options' => $this->populateEvents(),
            ]
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'filter',
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
}