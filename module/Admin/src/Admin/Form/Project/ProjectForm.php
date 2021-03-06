<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 20/02/2018
 * Time: 08:36
 */

namespace Admin\Form\Project;

use Admin\Form\Instituition\InstituitionFieldset;
use Application\Entity\File\File;
use Application\Entity\Institution\Institution;
use Application\Entity\Project\Options;
use Application\Entity\Project\People;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\State;
use Application\Entity\User\User;
use Application\Service\EntityManagerAwareInterface;
use Doctrine\Common\Collections\Collection;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

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
            'name' => 'user',
            'attributes' => [
                'id' => 'author'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'registration',
            'options' => [
                'label' => 'Regulamento',
                'value_options' => $this->populateRegulations(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'id' => 'registration',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'empty_option' => 'Selecione o status',
                'value_options' => Status::toArray()
            ],
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título original'
            ],
            'attributes' => [
                'placeholder' => 'Informe o título do projeto',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'english_title',
            'options' => [
                'label' => 'Versão em inglês'
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
                'label' => 'UF de produção',
                'value_options' => $this->populateStates(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'options[phase]',
            'options' => [
                'label' => 'Fase em que o projeto se encontra - Campo antigo',
                'value_options' => $this->populateOptions(Options::PHASE),
                'empty_option' => 'Selecione',
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'project_phase',
            'options' => [
                'label' => 'Fase em que o projeto se encontra',
                'help-block' => 'Descreva de forma clara quais as etapas já concluídas do projeto e quais os próximos passos previstos <br /> máximo de 1.500 caracteres',
                'rows' => '5'
            ],
            'attributes' => [
                'required' => 'required',
                'data-parsley-maxlength' => "1500",
                'data-parsley-trigger' => 'keyup'
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[category]',
            'options' => [
                'label' => 'Categoria na qual o projeto se enquadra',
                'value_options' => $this->populateOptions(Options::CATEGORY),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'project_category'
            ],
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'producers',
            'options' => [
                'label' => 'Produtor(es)',
                'count' => 1,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => [
                    'type' => ProductorFieldset::class
                ]
            ]

        ]);

        $this->add([
            'type' => InstituitionFieldset::class,
            'name' => 'instituition'
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'directors',
            'options' => [
                'label' => 'Diretor(es)',
                'count' => 1,
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
                'help-block' => 'máximo de 3.000 caracteres'
            ],
            'attributes' => [
                'rows' => '15',
                'required' => 'required',
                'data-parsley-maxlength' => "3000",
                'data-parsley-trigger' => 'keyup'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'short_sinopse',
            'options' => [
                'label' => 'Sinopse curta',
                'help-block' => 'máximo de 500 caracteres'
            ],
            'attributes' => [
                'required' => 'required',
                'rows' => '6',
                'data-parsley-maxlength' => "500",
                'data-parsley-trigger' => 'keyup'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'short_sinopse_english',
            'options' => [
                'label' => 'Sinopse curta em inglês',
                'help-block' => 'máximo de 500 caracteres'
            ],
            'attributes' => [
                'required' => 'required',
                'rows' => '6',
                'data-parsley-maxlength' => "500",
                'data-parsley-trigger' => 'keyup'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'long_sinopse',
            'options' => [
                'label' => 'Sinopse Longa',
                'help-block' => 'máximo 1.500 caracteres'
            ],
            'attributes' => [
                'rows' => '15',
                'required' => 'required',
                'data-parsley-maxlength' => "1500",
                'data-parsley-trigger' => 'keyup'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'argument',
            'options' => [
                'label' => 'Argumento ',
                'help-block' => 'máximo de 3.000 caracteres'
            ],
            'attributes' => [
                'rows' => '8',
                'required' => 'required',
                'data-parsley-maxlength' => "3000",
                'data-parsley-trigger' => 'keyup'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'director_notes',
            'options' => [
                'label' => 'Notas do(a) diretor(a) e conceito visual',
                'help-block' => 'Descrição das referências técnicas e visuais que serão aplicadas à estética e linguagem do filme. Máximo 5.000 caracteres'
            ],
            'attributes' => [
                'rows' => '15',
                'required' => 'required',
                'data-parsley-maxlength' => "5000",
                'data-parsley-trigger' => 'keyup'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'producer_notes',
            'options' => [
                'label' => 'Notas do(a) produtor(a)',
                'help-block' => 'Descrição das referências, interesses e desafios na produção do filme. Máximo 5.000 caracteres'
            ],
            'attributes' => [
                'rows' => '10',
                'required' => 'required',
                'data-parsley-maxlength' => "5000",
                'data-parsley-trigger' => 'keyup'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'movie_length_hour',
            'options' => [
                'label' => 'Hora',
                'value_options' => [
                    '1' => '01',
                    '2' => '02',
                    '3' => '03'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'name' => 'duration',
            'options' => [
                'label' => 'Duração exata',
                'help-block' => 'Formato do campo: hh:mm:seg'
            ],
            'attributes' => [
//                'required' => 'required',
                'data-inputmask' => "'alias': 'hh:mm:ss'"
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
                'required' => 'required',
                'rows' => 7,
                'data-parsley-maxlength' => "500",
                'data-parsley-trigger' => 'keyup'
            ]
        ]);

        $this->add([
            'name' => 'estimated_value',
            'options' => [
                'label' => 'Orçamento do projeto',
                'help-block' => 'Estimativa de orçamento, definida em ordem de grandeza, não sendo necessário detalhamento das rubricas'
            ],
            'attributes' => [
                'placeholder' => 'R$ ',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'related_partners',
            'options' => [
                'label' => 'Relacionar Parceiros Associados ao Projeto',
                'help-block' => 'Informar parcerias ou patrocínios que estão associados ao projeto, relacionando quais os nomes e percentual desta participação em relação ao orçamento do total'
            ],
            'attributes' => [

                'rows' => 10
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'value_captured_resources',
            'options' => [
                'label' => 'Porcentagem do valor total do projeto já captada em recursos',
            ],
            'attributes' => [
                'required' => 'required',
                'rows' => 5
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'value_captured_services',
            'options' => [
                'label' => 'Porcentagem do valor total do projeto já captada em serviços',
            ],
            'attributes' => [
                'required' => 'required',
                'rows' => 5
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[written_script]',
            'options' => [
                'label' => 'O Projeto já tem roteiro escrito?',
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
                'label' => 'Este é o primeiro ou segundo projeto de longa do(a) diretor(a)? ',
                'value_options' => $this->populateOptions(Options::FIRST_OR_SECOND_PROJECT),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'movie_link',
            'options' => [
                'label' => 'Link do filme',
            ],
            'attributes' => [
                'required' => 'required',
                'rows' => 3
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'movie_pass',
            'options' => [
                'label' => 'Senha de acesso ao link (se necessário)',
            ],
            'attributes' => [
                'rows' => 3
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'participated_other_festivals',
            'options' => [
                'label' => 'O filme já foi inscrito para participar de festivais (nacionais ou internacionais)?',
                'help-block' => 'Incluir status de cada inscrição (em análise, selecionado ou não selecionado)'
            ],
            'attributes' => [
                'rows' => 3,
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => ImageFieldset::class,
            'name' => 'image',
            'options' => [
                'label' => 'Imagem do Projeto '
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => FileFieldset::class,
            'name' => 'script',
            'options' => [
                'label' => 'Anexo - Roteiro ou Pré-roteiro'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'files',
            'options' => [
                'label' => 'Anexo - Extras ',
                'count' => 2,
                'should_create_template' => false,
                'target_element' => [
                    'type' => FileFieldset::class
                ]
            ]

        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'links',
            'options' => [
                'label' => 'Informar links e senhas, se houver, para acesso ao material audiovisual relacionado ao projeto (trailer, teaser, etc)',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'name' => 'year_of_participation',

            'options' => [
                'label' => 'Ano em que participou do Brasil CineMundi '
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'user' => [
                'name'       => 'user',
                'required'   => false,
            ],
            'registration' => [
                'name'       => 'registration',
                'required'   => false,
                'allow_empty' => true
            ],
            'status' => [
                'name'       => 'status',
                'required'   => false,
                'allow_empty' => true
            ],
            'phase' => [
                'name' => 'options[phase]',
                'required'   => false,
                'allow_empty' => true
            ]
        ]));
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
            ->findBy(['status'=>true]);

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
        if(!empty($data['user'])) {
            $user = $data['user'];
            if($user instanceof User) {
                $data['user'] = $user->getId();
            }
        }

        if(!empty($data['registration']) && $data['registration'] instanceof Registration) {
            $reg = $data['registration'];
            $data['registration'] = $reg->getId();
        }

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

        $files = [];
        if(isset($data['files'])) {
            if(count($data['files'])) {
                $count = 1;
                foreach ($data['files'] as $key=>$m) {
                    if(is_object($m)) {
                        $files[] = [
                            'id' => $m->getId(),
                            'description' => $m->getDescription(),
                            'src' => $m->getSrc(),
                            'is_default' => $m->getIsDefault()
                        ];
                    } elseif(!empty($m['file'])) {
                        $files[] = [
                            'file' => $m['file']
                        ];
                    }
                }
            }
        }
        $data['files'] = $files;

        if(!empty($data['image']) && $data['image'] instanceof File) {
            $image = $data['image'];
            $data['image'] = $image->toArray();
        } elseif(!empty($data['image']['file'])) {
            $data['image'] = [
                'file' => $data['image']['file']
            ];
        }

        if(!empty($data['script']) && $data['script'] instanceof File) {
            $script = $data['script'];
            $data['script'] = $script->toArray();
        } elseif(!empty($data['script']['file'])) {
            $data['script'] = [
                'file' => $data['script']['file']
            ];
        }

        if(!empty($data['instituition']) && $data['instituition'] instanceof Institution) {
            $instituition = $data['instituition'];
            $data['instituition'] = $instituition->toArray();
        }

        if(!empty($data['peoples']) && $data['peoples'] instanceof Collection) {
            foreach ($data['peoples'] as $people) {
                if($people->getType() == People::TYPE_DIRECTOR) {
                    $data['directors'][] = $people->toArray();
                } elseif($people->getType() == People::TYPE_PRODUCER) {
                    $data['producers'][] = $people->toArray();
                }
            }
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

    public function populateRegulations()
    {
        $regulations = [];
        if($this->getEntityManager()) {
            $coll = $this
                ->getEntityManager()
                ->getRepository(Registration::class)
                ->findBy([
                    'type' => Type::PROJECT_CINEMUNDI
                ], ['startDate'=>'DESC']);

            foreach ($coll as $c) {
                $regulations[$c->getId()] = $c->getName();
            }
        }
        return $regulations;
    }
}