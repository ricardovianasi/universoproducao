<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 20/02/2018
 * Time: 08:36
 */

namespace Admin\Form\Project;

use Admin\Form\MediaFieldset;
use Application\Entity\Project\Options;
use Application\Entity\State;
use Application\Service\EntityManagerAwareInterface;
use Zend\Form\Form;

class ProjectForm extends Form
    implements EntityManagerAwareInterface
{
    protected $em;
    protected $projectOptions;

    public function __construct($enetityManager=null)
    {
        $this->em = $enetityManager;

        parent::__construct('project-form');
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'project-form default-form-actions enable-validators',
            'id' => 'submit_form'
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'author',
            'attributes' => [
                'id' => 'author'
            ]
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título'
            ],
            'attributes' => [
                'placeholder' => 'Informe o título do projeto',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'state_production',
            'options' => [
                'label' => 'Estado de produção',
                'value_options' => $this->populateStates(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[phase]',
            'options' => [
                'label' => 'Fase em que o projeto se encontra',
                'value_options' => $this->populateOptions(Options::PHASE),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'producers',
            'options' => [
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => [
                    'type' => ProductorFieldset::class
                ]
            ]

        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'directors',
            'options' => [
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => [
                    'type' => DirectorFieldset::class
                ]
            ]

        ]);

        $this->add([
            'type' => 'select',
            'name' => 'has_protocol_registration_law',
            'options' => [
                'label' => 'O projeto está inscrito na lei do audiovisual (lei nº.: 8685/93)?',
                'value_options' => [
                    '1' => 'Sim',
                    '0' => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'has_protocol_registration_law'
            ],
        ]);

        $this->add([
            'name' => 'protocol_registration_law',
            'options' => [
                'label' => 'Número do protocolo de Inscrição na Lei do Audiovisual'
            ],
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'presentation',
            'options' => [
                'label' => 'Apresentação do projeto',
                'help-block' => 'máximo 1.500 caracteres'
            ],
            'attributes' => [
                'maxlength' => 1500,
                'rows' => '4'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'short_sinopse',
            'options' => [
                'label' => 'Sinopse curta',
                'help-block' => 'máximo 500 caracteres'
            ],
            'attributes' => [
                'maxlength' => 500,
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'long_sinopse',
            'options' => [
                'label' => 'Sinopse longa',
                'help-block' => 'máximo 1.500 caracteres'
            ],
            'attributes' => [
                'maxlength' => 1500,
                'rows' => '4'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'argument',
            'options' => [
                'label' => 'Argumento ',
                'help-block' => 'máximo 1.500 caracteres'
            ],
            'attributes' => [
                'maxlength' => 1500,
                'rows' => '4'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'director_notes',
            'options' => [
                'label' => 'Notas do(a) diretor(a) e conceito visual',
                'help-block' => 'Descrição das referências técnicas e visuais que serão aplicadas à estética e linguagem do filme. Máximo 3.000 caracteres'
            ],
            'attributes' => [
                'maxlength' => 3000,
                'rows' => '6'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'movie_length_hour',
            'options' => [
                'label' => 'Hora',
                'value_options' => [
                    '1' => '01',
                    '2' => '02'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'movie_length_minutes',
            'options' => [
                'label' => 'Minutos',
                'value_options' => $this->populateMinutes(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[genre]',
            'options' => [
                'label' => 'Gênero',
                'value_options' => $this->populateOptions(Options::GENRE),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[format]',
            'options' => [
                'label' => 'Formato de filmagem',
                'value_options' => $this->populateOptions(Options::FORMAT),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[display_format]',
            'options' => [
                'label' => 'Formato final de exibição',
                'value_options' => $this->populateOptions(Options::DISPLAY_FORMAT),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'name' => 'estimated_time_filming',
            'options' => [
                'label' => 'Previsão de número de semanas para filmagem',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'locations',
            'options' => [
                'label' => 'Locações',
                'help-block' => 'Citar se há cidades ou monumentos primordiais ao projeto. Máximo de 500 caracteres'
            ],
            'attributes' => [
                'maxlength' => 500,
            ]
        ]);

        $this->add([
            'name' => 'estimated_value',
            'options' => [
                'label' => 'Orçamento do projeto',
                'help-block' => 'Estimativa de orçamento, definida em ordem de grandeza, não sendo necessário detalhamento das rubricas'
            ],
            'attributes' => [
                'placeholder' => 'R$ '
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'related_partners',
            'options' => [
                'label' => 'Relacionar Parceiros Associados ao Projeto',
                'help-block' => 'Caso o projeto já esteja associado a algum tipo de parceria ou patrocínio, relacione quais os nomes e percentual desta participação em relação ao orçamento do seu projeto'
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'value_captured_resources',
            'options' => [
                'label' => 'Porcentagem do valor total de projetos já captada em recursos',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'value_captured_services',
            'options' => [
                'label' => 'Porcentagem do valor total de projetos já captada em serviços',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[written_script]',
            'options' => [
                'label' => 'O filme já tem roteiro escrito?',
                'value_options' => $this->populateOptions(Options::WRITTEN_SCRIPT),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[first_or_second_project]',
            'options' => [
                'label' => 'Este é o primeiro ou segundo projeto de longa do diretor? ',
                'value_options' => $this->populateOptions(Options::FIRST_OR_SECOND_PROJECT),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => MediaFieldset::class,
            'name' => 'image',
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'files',
            'options' => [
                'count' => 2,
                'should_create_template' => false,
                'target_element' => [
                    'type' => MediaFieldset::class
                ]
            ]

        ]);
    }

    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function populateStates()
    {
        $states = [];
        $list = $this->getEntityManager()->getRepository(State::class)->findBy([], ['name'=>'ASC']);

        foreach ($list as $l) {
            $states[$l->getId()] = $l->getName();
        }

        return $states;
    }

    protected function populateOptions($type)
    {
        if(!$this->projectOptions) {
            $this->prepareOptions();
        }

        if(!empty($this->projectOptions[$type])) {
            return $this->projectOptions[$type];
        }

        return [];
    }

    protected function prepareOptions()
    {
        $op = $this
            ->getEntityManager()
            ->getRepository(Options::class)
            ->findAll();

        $arrayOP = [];
        foreach ($op as $o) {
            $arrayOP[$o->getName()][$o->getId()] = $o->getLabel();
        }

        $this->projectOptions = $arrayOP;

        return $this;
    }

    public function populateMinutes()
    {
        $minutes = [];
        for($i=0; $i<=59; $i++) {
            $minutes[$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        return $minutes;
    }

    public function setData($data)
    {
        if(!empty($data['options'])) {
            foreach ($data['options'] as $key=>$op) {
                if(is_object($op)) {
                    $key = 'options['.$op->getName().']';
                    if(key_exists($key, $data)) {
                        if(!is_array($data[$key])) {
                            $data[$key] = (array) $data[$key];
                        }
                        $data[$key][] = $op->getId();
                    } else {
                        $data[$key] = $op->getId();
                    }
                } else {
                    $data['options['.$key.']'] = $op;
                }
            }
        }

        if(!empty($data['movie_length'])) {
            if(is_object($data['movie_length'])) {

                $movieLength = $data['movie_length'];

                $data['movie_length_hour'] = $movieLength->format('H');
                $data['movie_length_minutes'] = $movieLength->format('i');
            }
        }

        if(!empty($data['state_production']) && $data['state_production'] instanceof State) {
            $state = $data['state_production'];
            $data['state_production'] = $state->getId();
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

}