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
use Application\Entity\Seminar\Category;
use Application\Entity\Workshop\Pontuation;
use Zend\InputFilter\Factory as InputFilterFactory;

class SeminarRegistrationForm extends RegistrationForm
{
    public function __construct($em = null)
    {
        parent::__construct($em);

        $this->add([
            'type' => 'select',
            'name' => 'options['.Options::SEMINAR_CATEGORY.']',
            'options' => [
                'label' => 'Categoria dos debates',
                'value_options' => $this->populateSeminarCategories(),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'options['.Options::SEMINAR_AVALIABLE.']',
            'options' => [
                'label' => 'Quantidade de vagas',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'min' => 1
            ]
        ]);
        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            Options::SEMINAR_CATEGORY => [
                'name'       => 'options['.Options::SEMINAR_CATEGORY.']',
                'required'   => false,
                'allow_empty' => true
            ],
            Options::SEMINAR_AVALIABLE => [
                'name'       => 'options['.Options::SEMINAR_AVALIABLE.']',
                'required'   => false,
            ],
        ]));

    }

    public function populateSeminarCategories()
    {
        $options = [];
        if ($this->getEntityManager()) {
            $events = $this
                ->getEntityManager()
                ->getRepository(Category::class)
                ->findAll();

            foreach ($events as $e) {
                $options[$e->getId()] = $e->getName();
            }
        }
        return $options;
    }
}