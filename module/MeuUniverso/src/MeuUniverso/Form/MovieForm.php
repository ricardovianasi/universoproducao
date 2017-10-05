<?php
namespace MeuUniverso\Form;

use Admin\Form\Movie\MovieForm as AdminMovieForm;
use Application\Entity\Movie\Options;

class MovieForm extends AdminMovieForm
{
    public function __construct($entityManager, $registration=null)
    {
        parent::__construct($entityManager, Options::STATUS_ENABLED, $registration);

        $horizontalLayoutOptions = [
            'twb-layout' => 'horizontal',
            'column-size' => 'md-6',
            'label_attributes' => [
                'class' => 'col-md-4'
            ]
        ];

        $ignoreElements = ['events','accept_regulation'];

        foreach ($this->getElements() as $key=>$element) {
            if(!in_array($key, $ignoreElements)) {
                $element->setOptions($horizontalLayoutOptions);
            }
        }

        $this->setAttributes([
            'class' => 'form-horizontal movie-form'
        ]);
    }
}