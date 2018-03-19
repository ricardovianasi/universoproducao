<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:25
 */
namespace Admin\Form\EducationalProject;

use Admin\Form\EntityManagerTrait;
use Application\Entity\EducationalProject\Category;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Type;
use Zend\Form\Form;

class CategoryForm extends Form
{
    use EntityManagerTrait;

    public function __construct($em)
    {
        $this->setEntityManager($em);

        parent::__construct('educational-project-category-form');
        $this->setAttributes([
            'methor' => 'POST',
            'class' => 'default-form-actions enable-validators form-horizontal'
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Nome da categoria',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'registration',
            'options' => [
                'label' => 'Regulamento',
                'value_options' => $this->populateRegulations(),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);
    }

    public function populateRegulations()
    {
        $coll = [];
        $regs = $this
            ->getRepository(Registration::class)
            ->findBy(['type'=>Type::EDUCATIONAL_PROJECT], ['startDate'=>'DESC']);

        foreach ($regs as $reg) {
            $name = $reg->getName();
            if($reg->getEvent()) {
                $name.= ' ('.$reg->getEvent()->getShortName().')';
            }
            $coll[$reg->getId()] = $name;
        }
        return $coll;
    }

}