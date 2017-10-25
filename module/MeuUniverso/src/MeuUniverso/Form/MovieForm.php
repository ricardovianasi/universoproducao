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

        $this->get('events')->setAttributes([
            'type' => 'MultiCheckbox',
            'required' => true
        ])->setOptions([
            'label' => 'Autorizo a inscrição do filme para a seleção da',
            'value_options' => $this->populateEvents()
        ]);

        $ignoreElements = ['events','accept_regulation'];
        foreach ($this->getElements() as $key=>$element) {
            if(!in_array($key, $ignoreElements)) {
                /** @var Element $element */
                $element->setOption('twb-layout', 'horizontal')
                        ->setOption('column-size', 'md-6')
                        ->setLabelAttributes(['class' => 'col-md-4']);
            }
        }

        $this->remove('author');
        $this->remove('registration');

        $this->setAttributes([
            'class' => 'form-horizontal movie-form',

        ]);
    }
}