<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 03/05/2018
 * Time: 08:19
 */
namespace Admin\Form\SessionSchool;

use Admin\Form\Instituition\InstituitionFieldset;
use Application\Entity\Institution\Institution;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Type;
use Application\Entity\User\User;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class SessionSchoolSubscriptionForm extends Form
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
        $this->setAttributes([
            'id' => 'submit_form',
            'class' => 'default-form-actions'
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'user',
            'attributes' => [
                'class' => 'input-sm',
                'id' => 'user'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'registration',
            'options' => [
                'label' => 'Regulamento',
                'value_options' => $this->populateRegulations(),
                'empty_option' => 'Selecione'
            ],
            'attributes' => [
                'required' => 'required',
                'class' => 'trigger-form-reload'
            ]
        ]);


        $instituitionFieldset = new InstituitionFieldset('instituition');
        $instituitionFieldset->get('social_name')
            ->setLabel('Nome da escola');
        $instituitionFieldset->get('address')
            ->setLabel('Endereço completo da Instituição')
            ->setOption('help-block', 'Incluir nome do bairro');
        $instituitionFieldset->get('cep')
            ->setOption('help-block', 'Somente números')
            ->setAttribute('required', 'required');
        $instituitionFieldset->get('city')
            ->setAttribute('required', 'required');
        $instituitionFieldset->get('uf')
            ->setAttribute('required', 'required');
        $instituitionFieldset->get('phone')
            ->setOption('label', 'Telefone fixo')
            ->setAttribute('required', 'required');
        $instituitionFieldset->get('mobile_phone')
            ->setLabel('Telefone celular de contato')
            ->setAttribute('required', false);

        $this->add($instituitionFieldset);

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
                'min' => '1'
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

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'participants' => [
                'name' => 'participants',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'GreaterThan',
                        'options' => [
                            'min' => 1
                        ]
                    ]
                ]
            ]
        ]));
    }

    public function populateRegulations()
    {
        $regulations = [];
        if($this->getEntityManager()) {
            $coll = $this
                ->getEntityManager()
                ->getRepository(Registration::class)
                ->findBy([
                    'type' => Type::SESSION_SCHOOL
                ], ['startDate'=>'DESC']);

            foreach ($coll as $c) {
                $regulations[$c->getId()] = $c->getName();
            }
        }
        return $regulations;
    }

    public function setData($data)
    {
        if (!empty($data['registration']) && $data['registration'] instanceof Registration) {
            $reg = $data['registration'];
            $data['registration'] = $reg->getId();
        }

        if(!empty($data['instituition'])) {
            $instituition = $data['instituition'];
            if($instituition instanceof Institution) {
                $data['instituition'] = $instituition->toArray();
            }
        }

        if(!empty($data['user'])) {
            $user = $data['user'];
            if($user instanceof User) {
                $data['user'] = $user->getId();
            }
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
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


}