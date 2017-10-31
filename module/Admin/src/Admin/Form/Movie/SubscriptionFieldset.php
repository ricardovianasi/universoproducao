<?php
namespace Admin\Form\Movie;

use Application\Entity\Registration\Status;
use Zend\Form\Fieldset;

class SubscriptionFieldset extends Fieldset
{
    protected $entityManager;

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'event_name'
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'status',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => Status::toArray(),
                'label' => 'Status',
            ],
            'attributes' => [
            ]
        ]);
    }
}
