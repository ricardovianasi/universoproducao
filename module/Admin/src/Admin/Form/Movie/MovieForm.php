<?php
namespace Admin\Form\Movie;

use Application\Entity\Movie\Movie;
use Application\Entity\Movie\Options;
use Application\Entity\Movie\OptionsType;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Options as RegistrationOptions;
use Application\Entity\State;
use DoctrineModule\Validator\UniqueObject;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\Date;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;
use Zend\Validator\NotEmpty;
use Zend\Validator\Uri;

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
            'id' => 'submit_form'
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título'
            ],
            'attributes' => [
                'placeholder' => 'Informe o título do filme',
                'required' => 'required',
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
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'production_country',
            'options' => [
                'label' => 'País de produção',
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
                'help-block' => nl2br($this->getDurationHelpBlock())
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
            ],
            'attributes' => [
                'placeholder' => 'Informe o número do cpb',
                'required' => 'required'
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
                'required' => 'required'
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
                'required' => 'required'
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
                'required' => 'required',
                'id' => 'option_classification',
                'data-oficial-classification' => 'Classificação indicativa',
                'data-suggest-classification' => 'Indique a classificação indicativa sugerida'
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
                'label' => 'Para curta, indique se ele se enquadra em uma das categorias',
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
                'required' => 'required',
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
                'required' => 'required'
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'production_company',
            'options' => [
                'label' => 'Empresa produtora',
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
            ],
            'attributes' => [
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
                'required' => 'required',
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
                'required' => 'required',
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
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'conversations_languages',
            'options' => [
                'label' => 'Informe em qual(is) idioma(s)',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'subtitles_languages',
            'options' => [
                'label' => 'Informe em qual(is) idioma(s)',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'conversations_list_languages',
            'options' => [
                'label' => 'Informe em qual(is) idioma(s)',
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'has_participated_other_festivals',
            'options' => [
                'label' => 'O filme já participou de outros festivais',
                'value_options' => [
                    '1' => 'Sim',
                    '0' => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'other_festivals',
            'options' => [
                'label' => 'Cite os festivais e prêmios recebidos',
                'help-block' => '',
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
                'required' => 'required',
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
            'type' => 'file',
            'name' => 'media_file_1',
            'attributes' => [
                'accept' => 'image/*'
            ],
            'options' => [
                'label' => 'Imagem 1'
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'media_caption_1',
            'options' => [
                'label' => 'Créditos da foto'
            ],
            'attributes' => [
                'placeholder' => 'Créditos da foto'
            ]
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'media_id_1',
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'media_src_1',
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
           'duration' => [
               'name' => 'duration',
               'required' => true,
               'validators' => [
                   [
                       'name' => Date::class,
                       'options' => [
                           'format' => 'H:i:s'
                       ]
                   ]
               ]
           ],
           'movie_link' => [
               'name' => 'movie_link',
               'required' => true
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
            'media_caption_1' => [
                'name'       => 'media_caption_1',
                'required'   => true,
            ],
            'media_file_1' => [
                'name' => 'media_file_1',
                'required'   => true,
                'validators' => [
                    new MimeType('image/png,image/jpg,image/jpeg'),
                    [
                        'name' => Size::class,
                        'options' => [
                            'min' => '800KB',
                            'max' => '2MB',
                            'messages' => [
                                Size::TOO_SMALL => "O tamanho mínimo do arquivo é 800KB",
                                Size::TOO_BIG => "O tamanho máximo do arquivo é 2MB"
                            ]
                        ]
                    ],
                    //new Size(['min'=>'800KB', 'max'=>'2MB'])
                ]
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

        if(isset($data['medias'])) {
            if(count($data['medias'])) {
                $count = 1;
                foreach ($data['medias'] as $key=>$m) {
                    $data["media_id_$count"] = $m->getId();
                    $data["media_caption_$count"] = $m->getCredits();
                    $data["media_src_$count"] = $m->getSrc();
                }
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
