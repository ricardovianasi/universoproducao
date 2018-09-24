<?php
namespace Admin\Form\Movie;

use Application\Entity\Movie\Movie;
use Application\Entity\Movie\Options;
use Application\Entity\Movie\OptionsType;
use Application\Entity\Movie\ProducingInstitution;
use Application\Entity\Registration\Options as RegistrationOptions;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Type;
use Application\Entity\State;
use Application\Entity\User\User;
use Zend\Form\Form;
use Zend\I18n\Validator\IsInt;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\GreaterThan;

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
            'class' => 'movie-form default-form-actions enable-validators',
            'id' => 'submit_form'
        ]);

        $this->add([
            'type' => ProducingInstitutionFieldset::class,
            'name' => 'producing_institution',
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'type',
            'options' => [
                'label' => 'Tipo de inscrição',
                'empty_option' => 'Selecione',
                'value_options' => Movie::getMovieTypes()
            ],
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'author',
            'attributes' => [
                'id' => 'author'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'is_invited',
            'options' => [
                'label' => 'Filme convidado?',
                'value_options' => [
                    1 => 'Sim',
                    0 => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'registration',
            'options' => [
                'label' => 'Regulamento',
                'value_options' => $this->populateRegulations(),
            ],
            'attributes' => [
                'id' => 'registration',
                'multiple' => 'multiple',
                'class' => 'form-control multi-select'
            ]
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'events',
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título'
            ],
            'attributes' => [
                'placeholder' => 'Informe o título do filme',
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'title_english',
            'options' => [
                'label' => 'Título em inglês'
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
                'value_options' => $this->populateStates(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'production_city',
            'options' => [
                'label' => 'Cidade de produção',
            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'production_country',
            'options' => [
                'label' => 'País de produção',
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Number',
            'name' => 'end_date_year',
            'options' => [
                'label' => 'Ano de finalização',
                //'value_options' => $this->populateEndDateYear(),
                //'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        /*$this->add([
            'type' => 'Number',
            'name' => 'end_date_year',
            'options' => [
                'label' => 'Ano de finalização',
            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);*/

        $this->add([
            'type' => 'Select',
            'name' => 'end_date_month',
            'options' => [
                'label' => 'Mês de finalização',
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
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'duration',
            'type' => 'text',
            'options' => [
                'label' => 'Duração em minutos',
                'help-block' => nl2br($this->getDurationHelpBlock())
            ],
            'attributes' => [
//                'required' => 'required',
                'data-inputmask' => "'min': '0'",
                'maxlength' => 3
            ],
        ]);

        $this->add([
            'name' => 'cpb',
            'options' => [
                'label' => 'Número do CPB',
            ],
            'attributes' => [
                'placeholder' => 'Informe o número do cpb',
                //'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'has_cpb',
            'options' => [
                'label' => 'O filme possui CPB?',
                'help-block' => 'Certificado de produto brasileiro emitido pela Ancine',
                'value_options' => [
                    1 => 'Sim',
                    0 => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'placeholder' => 'Informe o número do cpb',
//                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'has_official_classification',
            'options' => [
                'label' => 'O filme possui classificação indicativa oficial emitida pelo Ministério da Justiça?',
                'value_options' => [
                    '1' => 'Sim',
                    '0' => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required'
            ],
        ]);


        $this->add([
            'type' => 'select',
            'name' => 'options[classification]',
            'options' => [
                'label' => 'Classificação',
                'value_options' => $this->populateOptions(OptionsType::CLASSIFICATION),
                'empty_option' => 'Selecione',
                'help-block' => 'Para informações sobre os critérios de classificação indicativa, acesse: http://www.justica.gov.br/seus-direitos/classificacao/guia-pratico'
            ],
            'attributes' => [
//                'required' => 'required',
                'id' => 'option_classification',
                'data-oficial-classification' => 'Classificação indicativa',
                'data-suggest-classification' => 'Indique a classificação indicativa sugerida'
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[general_category]',
            'options' => [
                'label' => 'Categoria',
                'value_options' => $this->populateOptions(OptionsType::GENERAL_CATEGORY),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required'
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[format_completed]',
            'options' => [
                'label' => 'Formato em que o filme foi finalizado',
                'value_options' => $this->populateOptions(OptionsType::FORMAT_COMPLETED),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[window]',
            'options' => [
                'label' => 'Janela final para exibição',
                'value_options' => $this->populateOptions(OptionsType::WINDOW),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[sound]',
            'options' => [
                'label' => 'Som',
                'value_options' => $this->populateOptions(OptionsType::SOUND),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[color]',
            'options' => [
                'label' => 'Cor',
                'value_options' => $this->populateOptions(OptionsType::COLOR),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[genre]',
            'options' => [
                'label' => 'Gênero',
                'value_options' => $this->populateOptions(OptionsType::GENRE),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'MultiCheckbox',
            'name' => 'options[accessibility]',
            'options' => [
                'label' => 'Acessibilidade para pessoas com necessidades especiais?',
                'value_options' => $this->populateOptionAccessibility(),
                'empty_option' => 'Selecione',
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[feature_directed]',
            'options' => [
                'label' => 'SE O FILME FOR LONGA, indique quantos LONGAS metragens o diretor já dirigiu',
                'value_options' => $this->populateOptions(OptionsType::FEATURE_DIRECTED),
                'empty_option' => 'Selecione',
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'options[short_movie_category]',
            'options' => [
                'label' => 'Indique se ele se enquadra em uma das categorias',
                'value_options' => $this->populateOptions(OptionsType::SHORT_MOVIE_CATEGORY),
                'empty_option' => 'Selecione',
                'help-block' => 'marcar uma das categorias NÃO exclui o processo de seleção para participar da mostra principal'
            ]
        ]);

        $this->add([
            'name' => 'direction',
            'options' => [
                'label' => 'Direção',

            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'script',
            'options' => [
                'label' => 'Roteiro',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'content_scenes',
            'options' => [
                'label' => 'Informar o conteúdo das cenas',
                'help-block' => 'Exemplo: cenas de sexo, violência, uso de drogas, etc...'
            ],
            'attributes' => [
                //'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'production_company',
            'options' => [
                'label' => 'Empresa produtora',
                'help-block' => 'Se não houver, digite N/A'
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'direction_production',
            'options' => [
                'label' => 'Direção de produção',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'co_production',
            'options' => [
                'label' => 'Co-produção',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'executive_production',
            'options' => [
                'label' => 'Produção executiva',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'photography',
            'options' => [
                'label' => 'Fotografia',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'direction_art',
            'options' => [
                'label' => 'Direção de arte',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'editing_assembly',
            'options' => [
                'label' => 'Montagem',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'soundtrack',
            'options' => [
                'label' => 'Trilha sonora',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'direct_sound',
            'options' => [
                'label' => 'Som direto',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'mixing',
            'options' => [
                'label' => 'Mixagem',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'sound_editing',
            'options' => [
                'label' => 'Edição de som',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'scenography',
            'options' => [
                'label' => 'Cenografia',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'costume',
            'options' => [
                'label' => 'Figurino',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'cast',
            'options' => [
                'label' => 'Elenco',
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
                'help-block' => 'Máximo 700 caracteres com espaço'
            ],
            'attributes' => [
                'rows' => 7,
                'maxlength' => 700
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'synopsis_english',
            'options' => [
                'label' => 'Sinopse em inglês',
            ],
            'attributes' => [
                'rows' => 7,
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'filmography_director',
            'options' => [
                'label' => 'Filmografia da(s) diretora(s) ou do(s) diretor(es)',
            ],
            'attributes' => [
                'rows' => 5,
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'director_age',
            'options' => [
                'label' => 'Idade da(s) diretora(s) ou do(s) diretor(es)',
            ],
            'attributes' => [
                'rows' => 5,
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'director_gender_identity',
            'options' => [
                'label' => 'Identidade de gênero da(s) diretora(s) ou do(s) diretor(es)',
            ],
            'attributes' => [
                'rows' => 5,
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'director_ethnicity',
            'options' => [
                'label' => 'Raça/etnia da(s) diretora(s) ou do(s) diretor(es)',
            ],
            'attributes' => [
                'rows' => 5,
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'distributor',
            'options' => [
                'label' => 'Distribuidora',
                'help-block' => 'Se não houver, digite N/A'
            ],
            'attributes' => [
                'rows' => 5,
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'has_conversations_languages',
            'options' => [
                'label' => 'O filme possui diálogos?',
                'value_options' => [
                    '1' => 'Sim',
                    '0' => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                //'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'has_subtitles_languages',
            'options' => [
                'label' => 'O filme possui cópia legendada?',
                'value_options' => [
                    '1' => 'Sim',
                    '0' => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'has_conversations_list_languages',
            'options' => [
                'label' => 'O filme possui lista de diálogos?',
                'value_options' => [
                    '1' => 'Sim',
                    '0' => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'conversations_languages',
            'options' => [
                'label' => 'Informe em qual(is) idioma(s)',
            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'subtitles_languages',
            'options' => [
                'label' => 'Informe em qual(is) idioma(s)',
            ],
            'attributes' => [
                //'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'conversations_list_languages',
            'options' => [
                'label' => 'Informe em qual(is) idioma(s)',
            ],
            'attributes' => [
                //'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'has_participated_other_festivals',
            'options' => [
                'label' => 'O filme já foi exibido publicamente ou tem exibição prevista para 2018?',
                'value_options' => [
                    '1' => 'Sim',
                    '0' => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'other_festivals',
            'options' => [
                'label' => 'Onde foi ou será exibido? Cite também os festivais e eventuais prêmios recebidos',
                'help-block' => '',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'movie_divulgation',
            'options' => [
                'label' => 'Link com trecho de divulgação ou trailer do filme',
                'help-block' => 'Inserir link do Vimeo ou Youtube (link aberto)',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'movie_link',
            'options' => [
                'label' => 'Link de acesso ao filme',
                'help-block' => 'A organização do evento não se responsabiliza por links incorretos. O filme poderá ser excluído do processo de seleção caso não seja possível ter acesso e/ou  visualização do mesmo',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'movie_password',
            'options' => [
                'label' => 'Senha de acesso ao filme',
                'help-block' => 'Se for necessário senha para acesso ao filme informe nesse campo',
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'medias',
            'options' => [
                'label' => 'Imagem',
                'count' => 1,
                'should_create_template' => false,
                'target_element' => [
                    'type' => MediaFieldset::class
                ]
            ]

        ]);

        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'medias' => [
                'name' => 'medias',
                'required' => true
            ],
            'registration' => [
                'name'       => 'registration',
                'required'   => false,
                'allow_empty' => true
            ],
           'duration' => [
               'name' => 'duration',
               'required' => true,
               'validators' => [
                   [
                       'name' => IsInt::class
                   ],
                   [
                        'name' => GreaterThan::class,
                       'options' => [
                           'min' => 0
                       ]
                   ]
               ]
           ],
           'movie_link' => [
               'name' => 'movie_link',
               'required' => false
           ],
            'options[accessibility]' => [
                'name'       => 'options[accessibility]',
                'required'   => false,
                'allow_empty' => true
            ],
            'options[feature_directed]' => [
               'name'       => 'options[feature_directed]',
               'required'   => false,
               'allow_empty' => true
            ],
            'options[short_movie_category]' => [
               'name'       => 'options[short_movie_category]',
               'required'   => false,
               'allow_empty' => true
            ],
            'options[general_category]' => [
               'name'       => 'options[short_movie_category]',
               'required'   => false,
               'allow_empty' => true
            ],
            'production_state' => [
                'name'       => 'production_state',
                'required'   => false,
                'allow_empty' => true
            ],
            'production_country' => [
                'name'       => 'production_country',
                'required'   => false,
                'allow_empty' => true
            ],
            'end_date_month' => [
                'name'       => 'end_date_month',
                'required'   => false,
                'allow_empty' => true
            ],
            'has_cpb' => [
                'name'       => 'has_cpb',
                'required'   => false,
                'allow_empty' => true
            ],
            'has_official_classification' => [
                'name'       => 'has_official_classification',
                'required'   => false,
                'allow_empty' => true
            ],
            'options[classification]' => [
                'name'       => 'options[classification]',
                'required'   => false,
                'allow_empty' => true
            ],
            'options[format_completed]' => [
                'name'       => 'options[format_completed]',
                'required'   => false,
                'allow_empty' => true
            ],
            'options[window]' => [
                'name'       => 'options[window]',
                'required'   => false,
                'allow_empty' => true
            ],
            'options[sound]' => [
                'name'       => 'options[sound]',
                'required'   => false,
                'allow_empty' => true
            ],
            'options[color]' => [
                'name'       => 'options[color]',
                'required'   => false,
                'allow_empty' => true
            ],
            'options[genre]' => [
                'name'       => 'options[genre]',
                'required'   => false,
                'allow_empty' => true
            ],
            'has_conversations_languages' => [
                'name'       => 'has_conversations_languages',
                'required'   => false,
                'allow_empty' => true
            ],
            'has_subtitles_languages' => [
                'name'       => 'has_subtitles_languages',
                'required'   => false,
                'allow_empty' => true
            ],
            'has_participated_other_festivals' => [
                'name'       => 'has_participated_other_festivals',
                'required'   => false,
                'allow_empty' => true
            ],
            'has_conversations_list_languages' => [
                'name'       => 'has_conversations_list_languages',
                'required'   => false,
                'allow_empty' => true
            ],
            'is_invited' => [
                'name'       => 'is_invited',
                'required'   => false,
                'allow_empty' => true
            ],

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

    public function populateOptionAccessibility()
    {
        if(!$this->options) {
            $this->prepareOptions();
        }

        $valueOptions = [];
        if($options = $this->options[OptionsType::ACCESSIBILITY]) {
            foreach ($options as $id=>$opName) {
                $valueOptions[] = [
                    'value' => $id,
                    'label' => $opName,
                    'attributes' => [
                        'id' => 'op_'.$id,
                        'class' => 'icheck'
                    ]
                ];
            }
        }

        return $valueOptions;
    }

    protected function prepareOptions()
    {
        $op = $this
            ->getEntityManager()
            ->getRepository(Options::class)
            ->findBy(['status'=>$this->optionsDefaultStatus], ['name'=>'ASC']);

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

    public function populateRegulations()
    {
        $regulations = [];
        if($this->getEntityManager()) {
            $qb = $this
                ->getEntityManager()
                ->getRepository(Registration::class)
                ->createQueryBuilder('m');
            $qb
                ->andWhere($qb->expr()->in('m.type', ':types'))
                ->setParameter('types', [Type::MOTION_CITY_MOVIE, Type::MOVIE, Type::EDUCATIONAL_MOVIE]);

            $coll = $qb->getQuery()->getResult();
            foreach ($coll as $c) {
                $regulations[$c->getId()] = $c->getName();
            }
        }
        return $regulations;
    }

    public function setData($data)
    {
        if(!empty($data['author'])) {
            $author = $data['author'];
            if($author instanceof User) {
                $data['author'] = $author->getId();
            }
        }

        if(!empty($data['producing_institution']) && $data['producing_institution'] instanceof ProducingInstitution) {
            $instituition = $data['producing_institution'];
            $data['producing_institution'] = $instituition->toArray();
        }

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
                $hour = $duration->format('H');
                $minutes = $duration->format('i');
                $data['duration'] = ($hour*60)+$minutes;
            }
        }

        $medias = [];
        if(isset($data['medias'])) {
            if(count($data['medias'])) {
                $count = 1;
                foreach ($data['medias'] as $key=>$m) {
                    if(is_object($m)) {
                        $medias[] = [
                            'id' => $m->getId(),
                            'caption' => $m->getCredits(),
                            'src' => $m->getSrc()
                        ];
                    } else {
                        if(!empty($m['file']['name'])) {
                            $medias[] = [
                                'id' => isset($m['id'])?$m['id']:'',
                                'caption' => isset($m['caption'])?$m['caption']:'',
                                'src' => isset($m['src'])?$m['src']:'',
                                'file' => isset($m['file'])?$m['file']:[]
                            ];
                        }

                    }
                    /*$data["media_id_$count"] = $m->getId();
                    $data["media_caption_$count"] = $m->getCredits();
                    $data["media_src_$count"] = $m->getSrc();*/
                }
            }
        }
        $data['medias'] = $medias;

        if(!empty($data['subscriptions'])) {
            $events = [];
            $registrations = [];
            foreach ($data['subscriptions'] as $ms) {
                $events[] = $ms->getEvent()->getId();
                $registrations[] = $ms->getRegistration()->getId();
            }
            $data['events'] = $events;
            $data['registration'] = $registrations;
        }

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

        $description = "";

        $durationCurtaTo = $this->getRegistration()->getOption(RegistrationOptions::MOVIE_DURATION_CURTA_TO);
        if($durationCurtaTo) {
            $time = \DateTime::createFromFormat('H:i:s', $durationCurtaTo->getValue());
            $hour = (int) $time->format('H');
            $mim = (int) $time->format('i');
            $sec = (int) $time->format('s');

            $description.= "Curtas - Filmes brasileiros com duração de até ".(($hour*60)+$mim)." ";
            if($sec>0) {
                $description.= "'".str_pad($sec, '2', '0', STR_PAD_LEFT)." ";
            }
            $description.= "minutos;<br/>";
        }

        $durationMediaFrom = $this->getRegistration()->getOption(RegistrationOptions::MOVIE_DURATION_MEDIA_FROM);
        $durationMediaTo = $this->getRegistration()->getOption(RegistrationOptions::MOVIE_DURATION_MEDIA_TO);
        if($durationMediaFrom && $durationMediaTo) {

            $description.= "Médias - Filmes brasileiros com duração entre ";

            $time = \DateTime::createFromFormat('H:i:s', $durationMediaFrom->getValue());
            $hour = (int) $time->format('H');
            $mim = (int) $time->format('i');
            $sec = (int) $time->format('s');

            $description.=(($hour*60)+$mim);
//            if($sec) {
//                $description.="'".str_pad($sec, '2', '0', STR_PAD_LEFT);
//            }

            $time = \DateTime::createFromFormat('H:i:s', $durationMediaTo->getValue());
            $hour = (int) $time->format('H');
            $mim = (int) $time->format('i');
            $sec = (int) $time->format('s');

            $description.= " e ".(($hour*60)+$mim);
//            if($sec) {
//                $description.="'".str_pad($sec, '2', '0', STR_PAD_LEFT);
//            }
            $description.= " minutos;<br/>";
        }

        $durationLongaFrom = $this->getRegistration()->getOption(RegistrationOptions::MOVIE_DURATION_LONGA_FROM);
        if($durationLongaFrom) {
            $time = \DateTime::createFromFormat('H:i:s', $durationLongaFrom->getValue());
            $hour = (int)$time->format('H');
            $mim = (int)$time->format('i');
            $sec = (int)$time->format('s');

            $description .= "Longas - Filmes brasileiros com duração superior a " . (($hour * 60) + $mim) . " ";
            if ($sec > 0) {
                $description .= "'" . str_pad($sec, '2', '0',STR_PAD_LEFT) . " ";
            }
            $description .= "minutos;";
        }

        return $description;
    }

    public function populateStates()
    {
        $states = [];
        $list = $this->getEntityManager()->getRepository(State::class)->findBy([], ['name'=>'ASC']);

        foreach ($list as $l) {
            $states[$l->getName()] = $l->getName();
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
