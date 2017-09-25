<?php
namespace Admin\Form\Movie;

use Application\Entity\Movie\Options;
use Application\Entity\Movie\OptionsType;
use Application\Entity\Registration\Registration;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class MovieForm extends Form
{
    protected $options;
    protected $optionsDefaultStatus;
    protected $entityManager;
    protected $registration;

    public function __construct($entityManager, $optionsDefaultStatus=Options::STATUS_ENABLED, $registration)
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
            'name' => 'end_date_year',
            'options' => [
                'label' => 'Ano de finalização',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ],
                'value_options' => [
                    2017 => 2017,
                    2018 => 2018
                ],
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
                'help-block' => 'Formato hh:mm:ss',
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
            'name' => 'movie_option_classification',
            'options' => [
                'label' => 'Classificação',
                'value_options' => $this->populateOptions(OptionsType::CLASSIFICATION),
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
            'name' => 'movie_option_format',
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
            'name' => 'movie_option_category',
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
            'name' => 'movie_option_window',
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
            'name' => 'movie_option_sound',
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
            'name' => 'movie_option_color',
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
            'name' => 'movie_option_genre',
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
            'type' => 'select',
            'name' => 'movie_option_accessibility',
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
                'rows' => 7
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
            'name' => 'direction_photography',
            'options' => [
                'label' => 'Direção de fotografia',
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
                'label' => 'Montagem/Edição',
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
                'help-block' => 'Informe os festivais que o filme já participou',
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
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'file',
            'name' => 'media[1]'
        ]);
        $this->add([
            'type' => 'file',
            'name' => 'media[2]'
        ]);
        $this->add([
            'type' => 'file',
            'name' => 'media[3]'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'media_caption[1]',
            'attributes' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'media_caption[2]',
            'attributes ' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'media_caption[3]',
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
                'value_options' => $this->populateEvents(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-6',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ]
        ]);

        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'movie_option_accessibility' => [
                'name'       => 'movie_option_accessibility',
                'required'   => false,
                'allow_empty' => true
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
        if(!empty($data['classification']) && is_object($data['classification'])) {
            $data['classification'] = ($data['classification'])->getId();
        }

        if(!empty($data['format']) && is_object($data['format'])) {
            $data['format'] = ($data['format'])->getId();
        }

        if(!empty($data['category']) && is_object($data['category'])) {
            $data['category'] = ($data['category'])->getId();
        }

        if(!empty($data['window']) && is_object($data['window'])) {
            $data['window'] = ($data['window'])->getId();
        }

        if(!empty($data['sound']) && is_object($data['sound'])) {
            $data['sound'] = ($data['sound'])->getId();
        }
        if(!empty($data['color']) && is_object($data['color'])) {
            $data['color'] = ($data['color'])->getId();
        }

        if(!empty($data['genre']) && is_object($data['genre'])) {
            $data['genre'] = ($data['genre'])->getId();
        }

        if(!empty($data['accessibility']) && is_object($data['accessibility'])) {
            $data['accessibility'] = ($data['accessibility'])->getId();
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
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
