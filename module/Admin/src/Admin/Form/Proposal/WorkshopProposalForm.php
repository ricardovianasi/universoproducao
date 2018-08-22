<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 21/08/2018
 * Time: 10:06
 */

namespace Admin\Form\Proposal;

use Zend\Form\Form;

class WorkshopProposalForm extends Form
{
    public function __construct()
    {
        parent::__construct('workshop-proposal-form');
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'default-form-actions enable-validators'
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome da oficina'
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
                'label' => 'Objetivo geral da oficina'
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
                'label' => 'Conteúdo programático por dia/aula'
            ],
            'attributes' => [
                'rows' => 10,
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'offer',
            'options' => [
                'label' => 'Número de vagas'
            ],
            'attributes' => [
                'placeholder' => 'Número de vagas',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'hours',
            'options' => [
                'label' => 'Carga horária'
            ],
            'attributes' => [
                'placeholder' => 'Carga horária',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'hour_class',
            'options' => [
                'label' => 'Horas/Aula'
            ],
            'attributes' => [
                'placeholder' => 'Horas/Aula',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'days_duration',
            'options' => [
                'label' => 'Duração em dias'
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
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'participant_prerequisites',
            'options' => [
                'label' => 'Pré-requisitos dos alunos'
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
                'help-block' => 'xerox de apostila, filmes em dvd, folhas, etc'
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
                'help-block' => 'tv, dvd, datashow, câmera, ilha de edição, etc'
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
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'value_hour_class',
            'options' => [
                'label' => 'Valor hora aula'
            ],
            'attributes' => [
                'placeholder' => 'R$ ',
            ]
        ]);

        $this->add([
            'name' => 'total_value',
            'options' => [
                'label' => 'Valor total da oficina'
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
                'help-block' => 'máximo 500 caracteres'
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
                'label' => 'Nome para contato'
            ],
            'attributes' => [
                'placeholder' => 'Nome para contato',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'E-mail'
            ],
            'attributes' => [
                'placeholder' => 'E-mail',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'phones',
            'options' => [
                'label' => 'Telefone/Celular'
            ],
            'attributes' => [
                'placeholder' => 'Telefone/Celular',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'address',
            'options' => [
                'label' => 'Endereço'
            ],
            'attributes' => [
                'placeholder' => 'Endereço',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'cep',
            'options' => [
                'label' => 'Cep'
            ],
            'attributes' => [
                'placeholder' => 'Cep',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'city',
            'options' => [
                'label' => 'Cidade'
            ],
            'attributes' => [
                'placeholder' => 'Cidade',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'comments',
            'options' => [
                'label' => 'Comentários/Observações',
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

}