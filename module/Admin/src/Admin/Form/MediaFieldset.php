<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 30/10/2017
 * Time: 11:08
 */

namespace Admin\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;

class MediaFieldset extends Fieldset
    implements InputFilterProviderInterface
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'src',
        ]);

        $this->add([
            'name' => 'credits',
            'options' => [
                'label' => 'Créditos'
            ],
            'attributes' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título'
            ],
            'attributes' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);

        $this->add([
            'name' => 'description',
            'type' => 'Textarea',
            'options' => [
                'label' => 'Descrição'
            ],
            'attributes' => [
                'placeholder' => 'Legenda ou créditos'
            ]
        ]);

        $this->add([
            'type' => 'Checkbox',
            'name' => 'is_default',
            'options' => array(
                'label' => 'Destaque',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
        ]);

    }

    public function getInputFilterSpecification()
    {
        return [];
    }


}