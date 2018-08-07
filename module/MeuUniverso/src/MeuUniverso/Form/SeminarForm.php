<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 17/12/2017
 * Time: 11:18
 */

namespace MeuUniverso\Form;

use Admin\Form\Seminar\SeminarSubscriptionForm;
use Application\Entity\User\User;
use Zend\InputFilter\Factory;

class SeminarForm extends SeminarSubscriptionForm
{
    /**
     * @var User
     */
    private $user;

    public function __construct($user, $em, $registration=null)
    {
        parent::__construct($em, $registration);
        $this->user = $user;
        $inputFilterFactory = new Factory();

        $this->add([
            'name' => 'debates[]',
            'options' => [
                'label' => 'Debates'
            ]
        ]);

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
                'required' => 'required'
            ]
        ]);

        $this->remove('status');
        $this->remove('id');
        $this->remove('dateInit');
        $this->remove('dateEnd');
        $this->remove('event');
        $this->remove('workshop');
        $this->remove('user');
        $this->remove('user_search');
        $this->remove('registration');

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
        ]);
        if(!$user->hasDependents()) {
            $this->get('user')->setAttribute('disabled', 'disabled');
            $this->get('user')->setValue($user->getId());

            $this->getInputFilter()->add([
                'name'       => 'user',
                'required'   => false,
                'allow_empty' => true
            ]);

        } else {
            $this->get('user')->setOption('empty_option', 'Selecione');
            $this->get('user')->setAttribute('required', 'required');
        }
    }

    public function populateUser()
    {
        $users[$this->user->getId()] = 'Titular - '.$this->user->getName();
        foreach ($this->user->getDependents() as $dependent) {
            $users[$dependent->getId()] = 'Dependente - '.$dependent->getName();
        }
        return $users;
    }
}