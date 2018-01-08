<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 15:19
 */

namespace Admin\Form\Programing;

class GenericProgramingForm extends ProgramingForm
{
    public function __construct($em, $event = null)
    {
        parent::__construct($em, $event);

        foreach ($this->getElements() as $key=>$element) {

            /** @var Element $element */
            $element->setOption('twb-layout', '')
                ->setOption('column-size', '')
                ->setLabelAttributes(['class' => ''])
                ->setAttribute('required', '');

        }
    }

}