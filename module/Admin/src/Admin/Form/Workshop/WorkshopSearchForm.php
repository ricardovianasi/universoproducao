<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/10/2017
 * Time: 18:58
 */

namespace Admin\Form\Workshop;


use Application\Entity\Event\Event;

class WorkshopSearchForm extends WorkshopForm
{
    public function __construct($em = null)
    {
        parent::__construct($em);
        $this->setAttributes([
            'method' => 'get'
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'event',
            'options' => [
                'label' => 'Evento',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateEvents(),
            ]

        ]);

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