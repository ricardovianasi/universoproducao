<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 04/01/2018
 * Time: 09:48
 */

namespace Admin\Form\Movie;

use Application\Entity\Programing\Meta;
use Zend\Form\Fieldset;

class MetaProgramingFieldset extends Fieldset
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => Meta::ADDITIONAL_INFO,
            'type' => 'Textarea',
            'options' => [
                'label' => 'Informações adicionais'
            ]
        ]);

        $this->add([
            'name' => Meta::NATIONAL_PREMIERE,
            'type' => 'Select',
            'options' => [
                'label' => 'Pré estreia nacional',
                'empty_option' => 'Selecione',
                'value_options' => [
                    '1' => 'Não',
                    '2' => 'Sim',
                ],
            ]
        ]);

        $this->add([
            'name' => Meta::NATIONAL_PREMIERE,
            'type' => 'Select',
            'options' => [
                'label' => 'Pré estreia internacional',
                'empty_option' => 'Selecione',
                'value_options' => [
                    '1' => 'Não',
                    '2' => 'Sim',
                ],
            ]
        ]);
    }
}