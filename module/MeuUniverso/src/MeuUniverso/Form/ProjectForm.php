<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 20/02/2018
 * Time: 09:03
 */

namespace MeuUniverso\Form;


class ProjectForm extends \Admin\Form\Project\ProjectForm
{
    public function __construct($enetityManager, $category="")
    {
        parent::__construct($enetityManager);

        $this->setAttribute('class', 'project-category-reload project-form');
        $this->setAttribute('id', 'project-form');
        $this->setAttribute('data-js-validate', '');

        $this->add([
            'type' => 'Checkbox',
            'name' => 'accept_regulation',
            'options' => array(
                'label' => 'Eu li e estou de acordo com as condições descritas no regulamento',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'form-group' => true
            ),
            'attributes ' => [
                'required' => true
            ]
        ]);

        if($category == 20 || $category == 22) {
            $this->remove('year_of_participation');
        }

        if($category == 20 || $category == 21) {
            $this->remove('participated_other_festivals');
            $this->remove('movie_link');
            $this->remove('movie_pass');
        }

        if($category == 21 || $category == 22) {
            $this->remove('options[first_or_second_project]');
            $this->getInputFilter()->remove('options[first_or_second_project]');
            $this->remove('estimated_time_filming');
            $this->remove('locations');
            $this->remove('director_notes');
            $this->remove('script');
        }

        if($category == 22) {
            $this->remove('files');
            $this->remove('value_captured_resources');
            $this->remove('value_captured_services');
            $this->remove('links');
            $this->remove('estimated_value');
            $this->remove('options[format]');
            $this->getInputFilter()->remove('options[format]');
        }

        $this->remove('has_protocol_registration_law');
        $this->remove('short_sinopse');
        $this->remove('short_sinopse_english');
        $this->remove('argument');
        $this->remove('producer_notes');
        $this->remove('options[written_script]');
        $this->remove('options[phase]');

        $this->getInputFilter()->remove('has_protocol_registration_law');
        $this->getInputFilter()->remove('short_sinopse');
        $this->getInputFilter()->remove('short_sinopse_english');
        $this->getInputFilter()->remove('argument');
        $this->getInputFilter()->remove('producer_notes');
        $this->getInputFilter()->remove('options[written_script]');
        $this->getInputFilter()->remove('options[phase]');

    }
}