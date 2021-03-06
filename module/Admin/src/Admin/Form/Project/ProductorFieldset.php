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

        $this->remove('image');

        $this->get('description')->setOptions([
            'label' => 'Currículo',
            'help-block' => 'Citar longa(s) em que atuou como produtor <br /> Currículo  máximo 2.000 caracteres'
        ])->setAttribute('maxlength', 2000);
    }
}