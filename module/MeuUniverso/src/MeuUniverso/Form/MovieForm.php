<?php
namespace MeuUniverso\Form;

use Admin\Form\Movie\MovieForm as AdminMovieForm;
use Application\Entity\Movie\Options;
use Zend\Form\Element;

class MovieForm extends AdminMovieForm
{
    public function __construct($entityManager, $registration=null)
    {
        parent::__construct($entityManager, Options::STATUS_ENABLED, $registration);

        $ignoreElements = ['events','accept_regulation'];
        foreach ($this->getElements() as $key=>$element) {
            if(!in_array($key, $ignoreElements)) {
                /** @var Element $element */
                $element->setOption('twb-layout', 'horizontal')
                        ->setOption('column-size', 'md-6')
                        ->setLabelAttributes(['class' => 'col-md-4']);
            }
        }

        $this->setAttributes([
            'class' => 'form-horizontal movie-form'
        ]);
    }
}