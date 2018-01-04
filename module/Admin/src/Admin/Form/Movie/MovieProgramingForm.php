<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 04/01/2018
 * Time: 10:05
 */

namespace Admin\Form\Movie;

use Admin\Form\Programing\ProgramingForm;

class MovieProgramingForm extends  ProgramingForm
{
    public function __construct($em, $event = null)
    {
        parent::__construct($em, $event);

        $this->add([
            'type' => 'Select',
            'name' => 'sub_event',
            'options' => [
                'label' => 'Sub-mostra',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateSubEvents(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);
    }

    public function populateSubEvents()
    {
        $subEvents = [];
        if($this->getEvent()) {
            foreach ($this->getEvent()->getSubEvents() as $p) {
                $subEvents[$p->getId()] = $p->getName();
            }
        }

        return $subEvents;
    }

    public function setData($data)
    {
        if(empty($data['sub_event']) && is_object($data['sub_event'])) {
            $subEvent = $data['sub_event'];
            $data['sub_event'] = $subEvent->getId();
        }
        return parent::setData($data);
    }
}