<?php
namespace MeuUniverso\Form;

use Admin\Form\Movie\MovieForm as AdminMovieForm;
use Application\Entity\Movie\Options;
use Zend\Form\Element;

class EducationalMovieForm extends AdminMovieForm
{
    public function __construct($entityManager, $registration=null)
    {
        parent::__construct($entityManager, Options::STATUS_ENABLED, $registration);

        $this->remove('events');
        $this->remove('type');
        //$this->remove('end_date_year');

        $this->add([
            'type' => \Admin\Form\Movie\ProducingInstitutionFieldset::class,
            'name' => 'institution',
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'duration_minutes',
            'options' => [
                'label' => 'Minutos',
                'value_options' => [
                    0 => '00',
                    1 => '01',
                    2 => '02',
                    3 => '03',
                ],
                'empty_option' => 'Minutos',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'duration_seconds',
            'options' => [
                'label' => 'Segundos',
                'value_options' => $this->populateSeconds(),
                'empty_option' => 'Segundos',
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Checkbox',
            'name' => 'accept_regulation',
            'options' => array(
                'label' => 'Eu li e estou de acordo com as condições descritas no regulamento de inscrições de filmes 
                da Mostra Educação',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'attributes ' => [
                'required' => true
            ]
        ]);

        $this->get('title')->setAttribute('required', 'required');
        $this->get('end_date_year')->setAttribute('required', 'required');
        $this->get('end_date_month')->setAttribute('required', 'required');
        $this->get('conversations_languages')->setAttribute('required', 'required');
        $this->get('has_conversations_languages')->setAttribute('required', 'required');
        $this->get('has_subtitles_languages')->setAttribute('required', 'required');
        $this->get('has_conversations_list_languages')->setAttribute('required', 'required');
        $this->get('subtitles_languages')->setAttribute('required', 'required');
        $this->get('conversations_list_languages')->setAttribute('required', 'required');
        $this->get('options[format_completed]')->setAttribute('required', 'required');
        $this->get('options[window]')->setAttribute('required', 'required');
        $this->get('options[sound]')->setAttribute('required', 'required');
        $this->get('options[color]')->setAttribute('required', 'required');
        $this->get('options[genre]')->setAttribute('required', 'required');
        $this->get('direction')->setAttribute('required', 'required');
        $this->get('synopsis')->setAttribute('required', 'required');
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
        $this->getInputFilter()->get('end_date_year')->setRequired(true);
        $this->getInputFilter()->get('end_date_month')->setRequired(true);
        $this->getInputFilter()->get('has_conversations_languages')->setRequired(true);
        $this->getInputFilter()->get('has_subtitles_languages')->setRequired(true);
        $this->getInputFilter()->get('options[format_completed]')->setRequired(true);
        $this->getInputFilter()->get('options[window]')->setRequired(true);
        $this->getInputFilter()->get('options[sound]')->setRequired(true);
        $this->getInputFilter()->get('options[color]')->setRequired(true);
        $this->getInputFilter()->get('options[genre]')->setRequired(true);
        $this->getInputFilter()->get('direction')->setRequired(true);
        $this->getInputFilter()->get('synopsis')->setRequired(true);
        $this->getInputFilter()->get('movie_link')->setRequired(true);
        $this->getInputFilter()->get('duration_minutes')->setRequired(true);
        $this->getInputFilter()->get('duration_seconds')->setRequired(true);

        $this->getInputFilter()->remove('duration');
        $this->getInputFilter()->add([
            'name' => 'duration',
            'required' => 'false'
        ]);

        $this->setAttributes([
            'class' => 'form-horizontal movie-form',

        ]);
    }

    public function populateSeconds()
    {
        $coll = [];
        $count = 0;
        do {
            $coll[$count] = str_pad($count, 2, '0', STR_PAD_LEFT);
            $count++;
        } while($count < 60);

        return $coll;
    }

    public function setData($data)
    {
        if(isset($data['duration_minutes']) && isset($data['duration_seconds'])) {
            $time = '00:'
                . str_pad($data['duration_minutes'], 2, '0', STR_PAD_LEFT)
                . ':' . str_pad($data['duration_seconds'], 2, '0', STR_PAD_LEFT);
            $data['duration'] = \DateTime::createFromFormat('H:i:s', $time);
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }
}