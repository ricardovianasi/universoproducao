<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 17/12/2017
 * Time: 11:09
 */

namespace Admin\Form\Workshop;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Form\Form as EntityForm;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\Workshop\Workshop;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Between;

class WorkshopPontuationForm extends Form
{
    private $entityManager;
    private $registration;

    public function __construct()
    {
        parent::__construct('workshop-pontuation-form');
        $this->setAttributes([
            //'class' => 'form-horizontal movie-form',
            'id' => 'submit_form'
        ]);




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