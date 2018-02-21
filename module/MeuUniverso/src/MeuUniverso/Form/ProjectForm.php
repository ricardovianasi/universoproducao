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
    public function __construct($enetityManager)
    {
        parent::__construct($enetityManager);

        $this->add([
            'type' => 'Checkbox',
            'name' => 'accept_regulation',
            'options' => array(
                'label' => 'Eu li e estou de acordo com as condições descritas no regulamento',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'attributes ' => [
                'required' => true
            ]
        ]);
    }
}