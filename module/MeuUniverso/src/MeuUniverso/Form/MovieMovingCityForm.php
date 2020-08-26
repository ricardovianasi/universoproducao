<?php
namespace MeuUniverso\Form;

use Admin\Form\Movie\MovieForm as AdminMovieForm;
use Application\Entity\Movie\Options;
use Zend\Form\Element;

class MovieMovingCityForm extends AdminMovieForm
{
    public function __construct($entityManager, $registration=null)
    {
        parent::__construct($entityManager, Options::STATUS_ENABLED, $registration);

        $this->remove('production_state');
        $this->remove('events');
        //$this->remove('end_date_year');
        //$this->remove('type');

        $this->add([
            'type' => 'MultiCheckbox',
            'name' => 'events',
            'options' => [
                'label' => 'Autorizo a inscrição do filme para a seleção da',
                'value_options' => $this->populateEvents()
            ],
            'attributes' => [
                'required' => true
            ]
        ]);

        $this->add([

            'name' => 'production_state',
            'options' => [
                'label' => 'Estado de produção',
                'empty_option' => 'Selecione',
                'help-block' => 'Poderão ser inscritos filmes brasileiros produzidos dentro da Região Metropolitana de Belo Horizonte'
            ],
            'attributes' => [
//                'required' => 'required',
                'readonly' => 'readonly',
                'value' => 'Minas Gerais',
            ]
        ]);

        /*$this->add([
           'type' => 'select',
           'name' => 'end_date_year',
            'options' => [
                'label' => 'Ano de finalização',
                'value_options' => $this->populateEndDateYear(),
                'empty_option' => 'Selecione'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);*/

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

        $this->get('title')->setAttribute('required', 'required');
        $this->get('title_english')->setAttribute('required', 'required');
        $this->get('end_date_year')->setAttribute('required', 'required');
        $this->get('end_date_month')->setAttribute('required', 'required');
        $this->get('duration')->setAttributes([
            'required' => 'required',
            'data-inputmask' => "'alias': 'integer', 'rightAlign': 'false'",
        ]);
        $this->get('duration')->setAttribute('required', 'required');
//        $this->get('cpb')->setAttribute('required', 'required');
        $this->get('has_cpb')->setAttribute('required', 'required');
        $this->get('has_official_classification')->setAttribute('required', 'required');
        $this->get('content_scenes')->setAttribute('required', 'required');
        $this->get('conversations_languages')->setAttribute('required', 'required');
        $this->get('has_conversations_languages')->setAttribute('required', 'required');
        $this->get('has_subtitles_languages')->setAttribute('required', 'required');
        $this->get('has_conversations_list_languages')->setAttribute('required', 'required');
        $this->get('subtitles_languages')->setAttribute('required', 'required');
        $this->get('conversations_list_languages')->setAttribute('required', 'required');
        $this->get('options[general_category]')->setAttribute('required', 'required');
        $this->get('options[format_completed]')->setAttribute('required', 'required');
//        $this->get('options[window]')->setAttribute('required', 'required');
//        $this->get('options[sound]')->setAttribute('required', 'required');
        $this->get('options[color]')->setAttribute('required', 'required');
        $this->get('options[genre]')->setAttribute('required', 'required');
        $this->get('direction')->setAttribute('required', 'required');
        $this->get('filmography_director')->setAttribute('required', 'required');
        $this->get('synopsis_english')->setAttribute('required', 'required');
        $this->get('synopsis')->setAttribute('required', 'required');
        $this->get('has_participated_other_festivals')->setAttribute('required', 'required');
        $this->get('movie_link')->setAttribute('required', 'required');

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

        $this->getInputFilter()->get('movie_link')->setRequired(true);
        $this->getInputFilter()->get('title')->setRequired(true);
        $this->getInputFilter()->get('title_english')->setRequired(true);
        $this->getInputFilter()->get('end_date_year')->setRequired(true);
        $this->getInputFilter()->get('end_date_month')->setRequired(true);
        $this->getInputFilter()->get('duration')->setRequired(true);
        $this->getInputFilter()->get('has_cpb')->setRequired(true);
        $this->getInputFilter()->get('has_official_classification')->setRequired(true);
        $this->getInputFilter()->get('has_conversations_languages')->setRequired(true);
        $this->getInputFilter()->get('has_subtitles_languages')->setRequired(true);
        $this->getInputFilter()->get('options[format_completed]')->setRequired(true);
        $this->getInputFilter()->get('options[general_category]')->setRequired(true);
        $this->getInputFilter()->get('options[window]')->setRequired(false);
        $this->getInputFilter()->get('options[sound]')->setRequired(false);
        $this->getInputFilter()->get('options[color]')->setRequired(true);
        $this->getInputFilter()->get('options[genre]')->setRequired(true);
        $this->getInputFilter()->get('direction')->setRequired(true);
        $this->getInputFilter()->get('filmography_director')->setRequired(true);
        $this->getInputFilter()->get('synopsis_english')->setRequired(true);
        $this->getInputFilter()->get('synopsis')->setRequired(true);
        $this->getInputFilter()->get('has_participated_other_festivals')->setRequired(true);
        $this->getInputFilter()->get('movie_link')->setRequired(true);

        $this->setAttributes([
            'class' => 'form-horizontal movie-form',

        ]);
    }
}