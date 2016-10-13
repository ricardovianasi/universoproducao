<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 13/10/2016
 * Time: 10:09
 */

namespace Admin\Form\Event;

use Application\Entity\Event\EventType;
use Zend\Form\Form;

class EventSearchForm extends Form
{
    public function __construct()
    {
        parent::__construct('event-search');
        $this->setAttributes([
            'class' => 'post-search',
            'id' => 'post-search',
            'method' => 'GET'
        ]);

        $this->add([
            'name' => 'fullName',
            'attributtes' => [
                'placeholder' => 'Nome',
            ]
        ]);

        $this->add([
            'name' => 'startDate',
            'type' => 'TwbBundle\Form\Element\DatePicker',
            'attributtes' => [
                'placeholder' => 'De',
                'class' => 'form-control form-filter input-sm'
            ]
        ]);

        $this->add([
            'name' => 'endDate',
            'type' => 'TwbBundle\Form\Element\DatePicker',
            'attributtes' => [
                'placeholder' => 'Até',
                'class' => 'form-control form-filter input-sm'
            ]
        ]);

        $this->add([
            'name' => 'type',
            'type' => 'select',
            'options' => [
                'empty_option' => 'Todos',
                'value_options' => EventType::toArray()
            ]

        ]);

        $this->add([
            'name' => 'edition',
            'attributtes' => [
                'placeholder' => 'Todas',
            ]
        ]);
    }
}