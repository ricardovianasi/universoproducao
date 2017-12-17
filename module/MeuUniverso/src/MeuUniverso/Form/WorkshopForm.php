<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 17/12/2017
 * Time: 11:18
 */

namespace MeuUniverso\Form;

use Admin\Form\Workshop\WorkshopRegistrationForm;
use Application\Entity\User\User;

class WorkshopForm extends WorkshopRegistrationForm
{
    /**
     * @var User
     */
    private $user;

    public function __construct($user, $em, $registration=null)
    {
        parent::__construct($em, $registration);
        $this->user = $user;

        $this->add([
            'type' => 'Checkbox',
            'name' => 'accept_regulation',
            'options' => array(
                'label' => 'Eu li e estou de acordo com as condições descritas no regulamento acima',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'attributes ' => [
                'required' => true
            ]
        ]);

        if($this->user->hasDependents()) {
            $this->remove('user');
            $this->add([
                'type' => 'Select',
                'name' => 'user',
                'options' => [
                    'label' => 'Inscrição para',
                    'value_options' => $this->populateUser(),
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
        }
    }

    public function populateUser()
    {
        $users = [];
        if($this->user->hasDependents()) {
            $users[$this->user->getId()] = $this->user->getName();
            foreach ($this->user->getDependents() as $dependent) {
                $users[$dependent->getId()] = $dependent->getName();
            }
        }

        return $users;
    }
}