<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:25
 */
namespace Admin\Form\Art;

use Zend\Form\Form;

class ArtCategoryForm extends Form
{
    public function __construct()
    {
        parent::__construct('art-category-form');
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
    }

}