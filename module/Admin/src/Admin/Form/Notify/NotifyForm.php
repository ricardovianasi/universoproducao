<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 13/12/2017
 * Time: 16:19
 */

namespace Admin\Form\Notify;

use Application\Entity\Notify\NotifyStatus;
use Application\Entity\Registration\Type;
use Zend\Form\Form;

class NotifyForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'form-horizontal'
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'subject',
            'options' => [
                'label' => 'Assunto',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ],
                'help-block' => 'Campo assunto do e-mail'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'empty_option' => 'Selecione',
                'value_options' => NotifyStatus::toArray(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'type',
            'options' => [
                'label' => 'Tipo do comunicado',
                'empty_option' => 'Selecione',
                'value_options' => Type::toArray(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'message',
            'options' => [
                'label' => 'Texto da mensagem',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'class' => 'tinymce_minimal',
                'required' => 'required'
            ]
        ]);
    }
}