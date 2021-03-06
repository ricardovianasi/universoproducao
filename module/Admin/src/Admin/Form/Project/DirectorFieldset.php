<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 20/02/2018
 * Time: 10:03
 */

namespace Admin\Form\Project;


class DirectorFieldset extends PeopleFieldset
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->remove('image');

        $this->get('description')->setOptions([
            'label' => 'Biofilmografia',
            'help-block' => 'Especificar na filmografia trabalhos em curta, média e longa-metragem, etc. incluir links para os trabalhos, se for o caso'
        ]);

    }
}