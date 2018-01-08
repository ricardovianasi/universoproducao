<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 11/12/2017
 * Time: 10:13
 */

namespace Admin\Form\Workshop;


use Admin\Form\Programing\ProgramingForm;
use Application\Entity\Registration\Type;
use Application\Entity\Workshop\Workshop;

class WorkshopProgramingForm extends ProgramingForm
{

    public function __construct($em, $event=null)
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