<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:25
 */
namespace Admin\Form\ExternalUser;

use Application\Entity\User\Category;
use Zend\Form\Form;

class UserSubCategoryForm extends Form
{
    private $entityManager;

    public function __construct($em)
    {
        parent::__construct('art-category-form');
        $this->entityManager = $em;

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
                'placeholder' => 'Nome da sub categoria',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'parent',
            'options' => [
                'label' => 'Categoria pai',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ],
                'empty_option' => 'Selecione',
                'value_options' => $this->populate()
            ],
            'attributes' => [
                'placeholder' => 'Nome da categoria',
                'required' => 'required'
            ]
        ]);
    }

    public function populate()
    {
        $profiles = $this->entityManager
            ->getRepository(Category::class)
            ->findBy(['parent'=>null], ['name'=>'asc']);

        $array = [];
        foreach($profiles as $pr) {
            $array[$pr->getId()] = $pr->getName();
        }

        return $array;
    }

}