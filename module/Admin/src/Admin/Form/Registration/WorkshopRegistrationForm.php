<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/09/2017
 * Time: 08:54
 */

namespace Admin\Form\Registration;


use Application\Entity\Registration\Options;
use Application\Entity\Registration\Type;
use Zend\InputFilter\Factory as InputFilterFactory;

class WorkshopRegistrationForm extends RegistrationForm
{
    public function __construct($em = null)
    {
        parent::__construct($em);

        $this->add([
            'type' => 'select',
            'name' => 'options['.Options::WORKSHOP_FORM.']',
            'options' => [
                'label' => 'Formulário de cadastro',
                'value_options' => $this->populateWorkshopForms(),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        /*$this->add([
            'type' => 'select',
            'name' => 'options['.Options::WORKSHOP_PONTUATION.']',
            'options' => [
                'label' => 'Ficha de pontuação',
                'value_options' => [],
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);
        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            Options::WORKSHOP_FORM => [
                'name'       => 'options['.Options::WORKSHOP_FORM.']',
                'required'   => false,
                'allow_empty' => true
            ],
            Options::WORKSHOP_PONTUATION => [
                'name'       => 'options['.Options::WORKSHOP_PONTUATION.']',
                'required'   => false,
                'allow_empty' => true
            ],
        ]));*/

    }

    public function populateWorkshopForms()
    {
        $options = [];
        if ($this->getEntityManager()) {
            $events = $this
                ->getEntityManager()
                ->getRepository('Application\Entity\Form\Form')
                ->findBy(['type'=>Type::WORKSHOP]);

            foreach ($events as $e) {
                $options[$e->getId()] = $e->getName();
            }
        }
        return $options;
    }

}