<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 20/02/2018
 * Time: 10:03
 */

namespace Admin\Form\Project;


class ProductorFieldset extends PeopleFieldset
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->get('description')->setOptions([
            'label' => 'Currículo',
            'help-block' => 'máximo 1.000 caracteres'
        ])->setAttribute('maxlength', 1000);
    }
}