<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 03/05/2018
 * Time: 08:19
 */
namespace Admin\Form\SessionSchool;

use Admin\Form\Instituition\InstituitionFieldset;
use Zend\Form\Form;

class SessionSchoolSubscription extends Form
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

        parent::__construct('session-school-form');

        $this->add([
            'type' => 'hidden',
            'name' => 'user',
            'attributes' => [
                'class' => 'input-sm',
                'id' => 'user'
            ]
        ]);

        $this->add([
            'type' => InstituitionFieldset::class,
            'name' => 'instituition'
        ]);

        $this->add([
            'name' => 'instituition_direction',
            'options' => [
                'label' => 'Nome do(a) diretor(a)'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'instituition_direction_phone',
            'options' => [
                'label' => 'Telefone'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'instituition_direction_email',
            'options' => [
                'label' => 'E-mail'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'responsible',
            'options' => [
                'label' => 'Nome do(a) responsável'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'responsible_office',
            'options' => [
                'label' => 'Cargo'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'responsible_phone',
            'options' => [
                'label' => 'Telefone fixo'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'responsible_mobile_phone',
            'options' => [
                'label' => 'Telefone celular'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Number',
            'name' => 'participants',
            'options' => [
                'label' => 'Número de participantes da sessão',
                'help-block' => 'Considerar alunos e professores responsáveis'
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'series_age',
            'options' => [
                'label' => 'Série/Ano',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);
    }
}