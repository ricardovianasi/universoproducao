<?php
namespace Admin\Form\Movie;

use Application\Entity\Registration\Status;
use Zend\Form\Fieldset;

class SubscriptionFieldset extends Fieldset
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        if($entityManager) {
            $this->entityManager = $entityManager;
        }

        $this->add([
            'type' => 'Select',
            'name' => 'status',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => Status::toArray()
            ],
            'attributes' => [
            ]
        ]);

        /*$this->add([
            'type' => 'Select',
            'name' => 'subevent',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => $this->populateSubevents()
            ],
            'attributes' => [
            ]
        ]);*/
    }

    protected function populateSubevents()
    {
        $items= [];
        return $items;
    }
}
