<?php
namespace Admin\Form\Movie;

use Application\Entity\Movie\Options;
use Application\Entity\Movie\OptionsType;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Options as RegistrationOptions;
use Application\Entity\State;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;

class MovieForm extends Form
{
    protected $options;
    protected $optionsDefaultStatus;
    protected $entityManager;
    protected $registration;

    public function __construct($entityManager, $optionsDefaultStatus=Options::STATUS_ENABLED, $registration=null)
    {
        $this->entityManager = $entityManager;
        $this->optionsDefaultStatus = $optionsDefaultStatus;
        $this->registration = $registration;

        parent::__construct('movie-form');
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'form-horizontal',
            'id' => 'submit_form'
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Informe o título do filme',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'title_english',
            'options' => [
                'label' => 'Título em inglês',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Informe o título em inglês do filme'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'production_state',
            'options' => [
                'label' => 'Estado de produção',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'value_options' => $this->populateStates(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'end_date_year',
            'options' => [
                'label' => 'Ano de finalização',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'value_options' => $this->populateEndDateYear(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'end_date_month',
            'options' => [
                'label' => 'Mês de finalização',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'value_options' => [
                    1 => 'Janeiro',
                    2 => 'Fevereiro',
                    3 => 'Março',
                    4 => 'Abril',
                    5 => 'Maio',
                    6 => 'Junho',
                    7 => 'Julho',
                    8 => 'Agosto',
                    9 => 'Setembro',
                    10 => 'Outubro',
                    11 => 'Novembro',
                    12 => 'Dezembro',
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'duration',
            'options' => [
                'label' => 'Duração exata',
                'help-block' => $this->getDurationHelpBlock(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'data-inputmask' => "'alias': 'hh:mm:ss', 'placeholder':'_'"
            ],
        ]);

        $this->add([
            'name' => 'cpb',
            'options' => [
                'label' => 'Número do CPB',
                'help-block' => 'Certificado de produto brasileiro emitido pela Ancine',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Informe o número do cpb'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'has_official_classification',
            'options' => [
                'label' => 'O filme possui classificação oficial emitida pelo ministério da justiça',
                'value_options' => [
                    '1' => 'Sim',
                    '0' => 'Não'
                ],
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[classification]',
            'options' => [
                'label' => 'Classificação',
                'value_options' => $this->populateOptions(OptionsType::CLASSIFICATION),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'help-block' => 'Indique a classificação sugerida ou oficial emitida pelo Ministério da Justiça',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[format_completed]',
            'options' => [
                'label' => 'Formato em que o filme foi finalizado',
                'value_options' => $this->populateOptions(OptionsType::FORMAT_COMPLETED),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[category]',
            'options' => [
                'label' => 'Categoria',
                'value_options' => $this->populateOptions(OptionsType::CATEGORY),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[window]',
            'options' => [
                'label' => 'Janela final para exibição',
                'value_options' => $this->populateOptions(OptionsType::WINDOW),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[sound]',
            'options' => [
                'label' => 'Som',
                'value_options' => $this->populateOptions(OptionsType::SOUND),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[color]',
            'options' => [
                'label' => 'Cor',
                'value_options' => $this->populateOptions(OptionsType::COLOR),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
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
                'value_options' => $this->populateOptions(OptionsType::GENRE),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'MultiCheckbox',
            'name' => 'options[accessibility]',
            'options' => [
                'label' => 'Acessibilidade para pessoas com necessidades especiais?',
                'value_options' => $this->populateOptions(OptionsType::ACCESSIBILITY),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[feature_directed]',
            'options' => [
                'label' => 'SE O FILME FOR LONGA, indique quantos LONGAS metragens o diretor já dirigiu',
                'value_options' => $this->populateOptions(OptionsType::FEATURE_DIRECTED),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[short_movie_category]',
            'options' => [
                'label' => 'Para curta, indique se ele se enquadra em uma das categorias',
                'value_options' => $this->populateOptions(OptionsType::SHORT_MOVIE_CATEGORY),
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'help-block' => 'marcar uma das categorias NÃO exclui o processo de seleção para participar da mostra principal'
            ]
        ]);

        $this->add([
            'name' => 'direction',
            'options' => [
                'label' => 'Direção',
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

        $this->add([
            'type' => 'Textarea',
            'name' => 'script',
            'options' => [
                'label' => 'Roteiro',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'rows' => 5
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'production_company',
            'options' => [
                'label' => 'Empresa produtora',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'direction_production',
            'options' => [
                'label' => 'Direção de produção',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'co_production',
            'options' => [
                'label' => 'Co-produção',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'executive_production',
            'options' => [
                'label' => 'Produção executiva',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'photography',
            'options' => [
                'label' => 'Fotografia',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'direction_art',
            'options' => [
                'label' => 'Direção de arte',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'editing_assembly',
            'options' => [
                'label' => 'Montagem',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'soundtrack',
            'options' => [
                'label' => 'Trilha sonora',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'direct_sound',
            'options' => [
                'label' => 'Som direto',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'mixing',
            'options' => [
                'label' => 'Mixagem',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'sound_editing',
            'options' => [
                'label' => 'Edição de som',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'scenography',
            'options' => [
                'label' => 'Cenografia',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'costume',
            'options' => [
                'label' => 'Figurino',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'cast',
            'options' => [
                'label' => 'Elenco',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'rows' => 7
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'synopsis',
            'options' => [
                'label' => 'Sinopse',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'rows' => 7,
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'filmography_director',
            'options' => [
                'label' => 'Filmografia do diretor',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'subtitles_languages',
            'options' => [
                'label' => 'Idioma(s) das legendas',
                'help-block' => 'Se o filme possuir diálogos, informe o idioma nesse campo',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'conversations_list_languages',
            'options' => [
                'label' => 'Idioma(s) lista de diálogo',
                'help-block' => 'Se o filme possuir lista de diálogos, informe o idioma nesse campo',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'other_festivals',
            'options' => [
                'label' => 'Este filme já participou de outros festivais?',
                'help-block' => 'Cite os festivais e prêmios recebidos',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'movie_divulgation',
            'options' => [
                'label' => 'Link de divulgação',
                'help-block' => 'Inserir link do Vimeo ou Youtube',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'movie_link',
            'options' => [
                'label' => 'Link de acesso ao filme',
                'help-block' => 'A organização do evento não se responsabiliza por links incorretos. O filme poderá ser excluído do processo de seleção caso não seja possével ter acesso e/ou exibição do mesmo',
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

        $this->add([
            'type' => 'Textarea',
            'name' => 'movie_password',
            'options' => [
                'label' => 'Senha de acesso ao filme',
                'help-block' => 'Se for necessário senha para acesso ao filme informe nesse campo',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'file',
            'name' => 'media_file_1'
        ]);
        $this->add([
            'type' => 'file',
            'name' => 'media_file_2'
        ]);
        $this->add([
            'type' => 'file',
            'name' => 'media_file_3'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'media_caption_1',
            'attributes' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'media_caption_2',
            'attributes ' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'media_caption_3',
            'options' => [
            ],
            'attributes' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);


        $this->add([
            'type' => 'MultiCheckbox',
            'name' => 'events',
            'options' => [
                'label' => 'Autorizo a inscrição do filme para a seleção da',
                'value_options' => $this->populateEvents()
            ],
            'attributes ' => [
                'required' => true
            ]
        ]);

        $this->add([
            'type' => 'Checkbox',
            'name' => 'accept_regulation',
            'options' => array(
                'label' => 'Eu li e estou de acordo com as condições descritas no regulamento de inscrições de filmes',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'attributes ' => [
                'required' => true
            ]
        ]);

        //Validações
       $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'options_accessibility' => [
                'name'       => 'options_accessibility',
                'required'   => false,
                'allow_empty' => true
            ],
           'options_feature_directed' => [
               'name'       => 'options_feature_directed',
               'required'   => false,
               'allow_empty' => true
           ],
           'options_short_movie_category' => [
               'name'       => 'options_short_movie_category',
               'required'   => false,
               'allow_empty' => true
           ],
            'media_file_1' => [
                'name' => 'media_file_1',
                'required'   => false,
                'validators' => [
                    new MimeType('image/png,image/jpg'),
                    new Size(['min'=>'800KB', 'max'=>'2MB'])
                ]
            ],
           'media_file_2' => [
               'name' => 'media_file_2',
               'required'   => false,
               'validators' => [
                   new MimeType('image/png,image/jpg'),
                   new Size(['min'=>'800KB', 'max'=>'2MB'])
               ]
           ],
           'media_file_3' => [
               'name' => 'media_file_3',
               'required'   => false,
               'validators' => [
                   new MimeType('image/png,image/jpg'),
                   new Size(['min'=>'800KB', 'max'=>'2MB'])
               ]
           ]

        ]));
    }

    protected function populateOptions($type)
    {
        if(!$this->options) {
            $this->prepareOptions();
        }

        if(!empty($this->options[$type])) {
            return $this->options[$type];
        }

        return [];
    }

    protected function prepareOptions()
    {
        $op = $this
            ->getEntityManager()
            ->getRepository(Options::class)
            ->findBy(['status'=>$this->optionsDefaultStatus]);

       $arrayOP = [];
        foreach ($op as $o) {
            $arrayOP[$o->getType()][$o->getId()] = $o->getName();
        }

        $this->options = $arrayOP;

        return $this;
    }

    public function populateEvents()
    {
        $options = [];
        if($this->registration) {
            foreach ($this->registration->getEvents() as $reg) {
                $options[$reg->getId()] = $reg->getFullName();
            }
        }

        return $options;
    }

    public function setData($data)
    {

        if(!empty($data['options'])) {
            foreach ($data['options'] as $key=>$op) {
                if(is_object($op)) {
                    $key = 'options['.$op->getType().']';
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

        if(!empty($data['duration'])) {
            if(is_object($data['duration'])) {
                $duration = $data['duration'];
                $data['duration'] = $duration->format('H:i:s');
            }
        }

        $events = [];
        if(count($data['events'])) {
            foreach ($data['events'] as $key=>$e) {
                if(is_object($e)) {
                    $events[] = $e->getEvent()->getId();
                } else {
                    $events[] = $e;
                }
            }
        }
        $data['events'] = $events;

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

    public function populateEndDateYear()
    {
        $dateTo = new \DateTime();
        $years = [];

        if(!$this->getRegistration()) {
            return $years;
        }

        $movieFinishFrom = (string) $this->getRegistration()->getOption(RegistrationOptions::MOVIE_ALLOW_FINISHED_FROM);
        $movieFinishTo = (string) $this->getRegistration()->getOption(RegistrationOptions::MOVIE_ALLOW_FINISHED_TO);

        if($movieFinishFrom) {
            $dateFrom = \DateTime::createFromFormat('d/m/Y', $movieFinishFrom);
            if($movieFinishTo) {
                $dateTo = \DateTime::createFromFormat('d/m/Y', $movieFinishTo);
            }

            do {
                $years[$dateFrom->format('Y')] = $dateFrom->format('Y');

                $dateFrom->add(new \DateInterval('P1Y'));

            } while($dateFrom->format('Y') <= $dateTo->format('Y'));

        } else {
            return [
                $dateTo->format('Y') => $dateTo->format('Y')
            ];
        }

        return $years;
    }

    public function getDurationHelpBlock()
    {
        if(!$this->getRegistration()) {
            return;
        }

        return (string) $this
            ->getRegistration()
            ->getOption(RegistrationOptions::MOVIE_DURATION_OBS);
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

    public function populateEndMonthYear()
    {
        $month = [];
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return Registration|null
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param mixed $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }
}
