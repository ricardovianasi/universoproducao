<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 17/12/2017
 * Time: 11:09
 */

namespace Admin\Form\Workshop;

use Application\Entity\Form\Form as EntityForm;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Between;

class WorkshopRegistrationForm extends Form
{
    private $entityManager;
    private $registration;

    public function __construct($em, $registration=null)
    {
        if($em) {
            $this->entityManager = $em;
        }

        if($registration) {
            $this->registration = $registration;
        }

        parent::__construct('workshop-registration-form');
        $this->setAttributes([
            'class' => 'form-horizontal movie-form',
            'id' => 'submit_form'
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'user'
        ]);

        $this->add([
            'name' => 'age_of_user',
            'options' => [
                'label' => 'Idade do usuário'
            ]
        ]);

        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([

        ]));

        $subForm = new Fieldset('form_answer');

        if($this->getRegistration()) {
            if($formOption = $this->getRegistration()->getOption(Options::WORKSHOP_FORM)) {
                /** @var EntityForm $form */
                $form = $this
                    ->getEntityManager()
                    ->getRepository(EntityForm::class)
                    ->find($formOption->getValue());

                if($form) {

                    foreach ($form->getElements() as $el) {
                        $options = [];
                        if($elementOptions = $el->getOptions()) {
                            $options = $elementOptions;
                        }
                        $options['label'] = $el->getLabel();
                        $options['twb-layout'] = 'horizontal';
                        $options['column-size'] = 'md-6';
                        $options['label_attributes'] = [
                            'class' => 'col-md-4'
                        ];

                        $attributes = [];
                        if($elementAttributes = $el->getAttributes()) {
                            $attributes = $elementAttributes;
                        }

                        if($el->getRequired()) {
                            $attributes['required'] = 'required';
                        }

                        $subForm->add([
                            'name' => $el->getName(),
                            'type' => $el->getType(),
                            'options' => $options,
                            'attributes' => $attributes
                        ]);
                    }
                }
            }
        }

        $this->add($subForm);
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param null $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }
}