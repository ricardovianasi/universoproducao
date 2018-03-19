<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/03/2018
 * Time: 10:49
 */

namespace MeuUniverso\Form;


use Doctrine\ORM\EntityManager;

class EducationalProjectForm extends \Admin\Form\EducationalProject\EducationalProjectForm
{
    public function __construct(EntityManager $em, $registration = null)
    {
        parent::__construct($em, $registration, false);

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

        $this->setAttributes([
            'class' => 'form-horizontal',

        ]);
    }

}