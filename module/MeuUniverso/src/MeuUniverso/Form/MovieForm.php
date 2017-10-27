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

        $this->get('end_date_year')->setAttributes([
            'required' => 'required',
            'type' => 'Select'
        ])->setOptions([
            'label' => 'Ano de finalização',
            'value_options' => $this->populateEndDateYear(),
            'empty_option' => 'Selecione'
        ]);

        $this->add([
            'type' => 'Checkbox',
            'name' => 'accept_regulation',
            'options' => array(
                'label' => 'Eu li e estou de acordo com as condições descritas no regulamento de inscrições de filmes',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'attributes ' => [
                'required' => true
            ]
        ]);

        $this->get('cpb')->setAttribute('required', 'required');
        $this->get('content_scenes')->setAttribute('required', 'required');
        $this->get('conversations_languages')->setAttribute('required', 'required');
        $this->get('subtitles_languages')->setAttribute('required', 'required');
        $this->get('conversations_list_languages')->setAttribute('required', 'required');

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

        $this->getInputFilter()->get('media_file_1')->setRequired(true);
        $this->getInputFilter()->get('media_caption_1')->setRequired(true);
    }
}