<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 13/12/2017
 * Time: 09:17
 */

namespace Admin\Form\Project;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Registration\Status;
use Zend\Form\Form;

class StatusModalForm extends Form
{

    public function __construct()
    {
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
            ],
            'attributes' => [
                'id' => 'event'
            ]
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'filter',
        ]);
    }
}