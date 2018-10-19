<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 21/08/2018
 * Time: 10:06
 */

namespace Admin\Form\Proposal;

use Application\Entity\User\User;
use Zend\Form\Form;

class WorkshopProposalForm extends Form
{
    public function __construct()
    {
        parent::__construct('workshop-proposal-form');
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'default-form-actions enable-validators form-horizontal'
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'author',
            'attributes' => [
                'class' => 'input-sm',
                'id' => 'user'
            ]
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome da oficina',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Nome da oficina',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'objectives',
            'options' => [
                'label' => 'Objetivo geral da oficina',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'rows' => 10,
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'program_content',
            'options' => [
                'label' => 'Conteúdo programático por dia/aula',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'rows' => 10,
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'offer',
            'options' => [
                'label' => 'Número de vagas',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Número de vagas',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'hours',
            'options' => [
                'label' => 'Carga horária',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Carga horária',
                'required' => 'required',

            ]
        ]);

        $this->add([
            'name' => 'hour_class',
            'options' => [
                'label' => 'Horas/Aula',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Horas/Aula',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'days_duration',
            'options' => [
                'label' => 'Duração em dias',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Duração em dias',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'shift',
            'options' => [
                'label' => 'Turno',
                'value_options' => [
                    'Manhã' => 'Manhã',
                    'Noite' => 'Noite'
                ],
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'participant_prerequisites',
            'options' => [
                'label' => 'Pré-requisitos dos alunos',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]

            ],
            'attributes' => [
                'rows' => 10,
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'didactic_resources',
            'options' => [
                'label' => 'Recursos didáticos',
                'help-block' => 'xerox de apostila, filmes em dvd, folhas, etc',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]

            ],
            'attributes' => [
                'rows' => 10,
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'necessary_equipments',
            'options' => [
                'label' => 'Equipamentos necessários',
                'help-block' => 'tv, dvd, datashow, câmera, ilha de edição, etc',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'rows' => 10,
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'final_product',
            'options' => [
                'label' => 'Esta oficina terá produto final?',
                'value_options' => [
                    'Sim' => 'Sim',
                    'Não' => 'Não'
                ],
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'display_final_product',
            'options' => [
                'label' => 'Se sim, poderá ser exibido?',
                'value_options' => [
                    'Sim' => 'Sim',
                    'Não' => 'Não'
                ],
                'empty_option' => 'Selecione',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'value_hour_class',
            'options' => [
                'label' => 'Valor hora aula',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'R$ ',
            ]
        ]);

        $this->add([
            'name' => 'total_value',
            'options' => [
                'label' => 'Valor total da oficina',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'R$ ',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'instructors_curriculum',
            'options' => [
                'label' => 'Breve currículo do instrutor',
                'help-block' => 'máximo 500 caracteres',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'rows' => 10,
                'maxlength' => 500,
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'contact_name',
            'options' => [
                'label' => 'Nome para contato',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Nome para contato',
            ]
        ]);

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'E-mail',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'E-mail',
            ]
        ]);

        $this->add([
            'name' => 'phones',
            'options' => [
                'label' => 'Telefone/Celular',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Telefone/Celular',
            ]
        ]);

        $this->add([
            'name' => 'address',
            'options' => [
                'label' => 'Endereço',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Endereço',
            ]
        ]);

        $this->add([
            'name' => 'cep',
            'options' => [
                'label' => 'Cep',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Cep',
            ]
        ]);

        $this->add([
            'name' => 'city',
            'options' => [
                'label' => 'Cidade',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'placeholder' => 'Cidade',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'comments',
            'options' => [
                'label' => 'Comentários/Observações',
                'twb-layout' => 'horizontal',
                'column-size' => 'md-5',
                'label_attributes' => [
                    'class' => 'col-md-3'
                ]
            ],
            'attributes' => [
                'rows' => 10
            ]
        ]);

        $this->add([
            'name' => 'startDate',
            'type' => 'TwbBundle\Form\Element\DatePicker',
            'attributtes' => [
                'placeholder' => 'De',
                'class' => 'form-control form-filter input-sm'
            ]
        ]);

        $this->add([
            'name' => 'endDate',
            'type' => 'TwbBundle\Form\Element\DatePicker',
            'attributtes' => [
                'placeholder' => 'Até',
                'class' => 'form-control form-filter input-sm'
            ]
        ]);

    }

    public function setData($data)
    {
        if(!empty($data['author'])) {
            $author = $data['author'];
            if($author instanceof User) {
                $data['author'] = $author->getId();
            }
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

}