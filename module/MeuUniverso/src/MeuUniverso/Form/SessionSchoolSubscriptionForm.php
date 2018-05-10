<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 17/12/2017
 * Time: 11:18
 */

namespace MeuUniverso\Form;

use Admin\Form\SessionSchool\SessionSchoolSubscriptionForm as AdminSessionSchoolSubscriptionForm;

class SessionSchoolSubscriptionForm extends AdminSessionSchoolSubscriptionForm
{
    public function __construct($user, $em, $registration=null)
    {
        parent::__construct($em, $registration);

        $this->remove('user')
            ->remove('registration');

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
    }
}