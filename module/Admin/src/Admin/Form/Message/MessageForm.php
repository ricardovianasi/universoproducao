<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/03/2018
 * Time: 09:39
 */
namespace Admin\Form\Message;

use Admin\Form\EntityManagerTrait;
use Admin\Form\Project\FileFieldset;
use Application\Entity\EducationalProject\Category;
use Application\Entity\Message\MessageType;
use Application\Entity\Registration\Registration;
use Application\Entity\State;
use Doctrine\ORM\EntityManager;
use Psr\Log\InvalidArgumentException;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class MessageForm extends Form
{
    public function __construct()
    {
        parent::__construct('message-form');
        $this->setAttributes([
            'class' => 'form-horizontal message-form default-form-actions enable-validators'
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'subject',
            'options' => [
                'label' => 'Assunto',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'type',
            'options' => [
                'label' => 'Tipo da mensagem',
                'value_options' => MessageType::toArray(),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'body',
            'options' => [
                'label' => 'Corpo da mensagem',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
            ],
            'attributes' => [
                'class' => 'tinymce_minimal',
                'rows' => '9',
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    '1' => 'Habilitado',
                    '0' => 'Desabilitado'
                ],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);
    }
}