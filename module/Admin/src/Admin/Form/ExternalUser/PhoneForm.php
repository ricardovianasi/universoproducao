<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 02/02/2016
 * Time: 09:57
 */

namespace Admin\Form\ExternalUser;

use Application\Entity\Phone\PhoneType;
use Zend\Form\Form;

class PhoneForm extends Form
{
	public function __construct()
	{
	    parent::__construct('phone');

		$this->add([
		    'name' => 'phone[ddd]',
            'options' => [
                'label' => 'DDD',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
                'id' => 'admin-phone-ddd'
            ]
        ]);

        $this->add([
            'name' => 'phone[number]',
            'options' => [
                'label' => 'Número do telefone',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
                'id' => 'admin-phone-number'
            ]
        ]);

        $this->add([
            'name' => 'phone[contact_name]',
            'options' => [
                'label' => 'Falar com',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
                'id' => 'admin-phone-contact_name'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'phone[type]',
            'options' => [
                'label' => 'Tipo',
                'value_options' => PhoneType::toArray(),
                'empty_option' => 'Selecione',
                'label_attributes' => [
                    'class'  => 'sr-only'
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
                'id' => 'admin-phone-type'
            ]
        ]);
	}
}