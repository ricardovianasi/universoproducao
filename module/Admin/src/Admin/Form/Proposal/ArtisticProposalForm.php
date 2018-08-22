<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 17/08/2018
 * Time: 15:34
 */

namespace Admin\Form\Proposal;

use Application\Entity\Proposal\ArtisticProposalCategory;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Form\Form;

class ArtisticProposalForm extends Form
{
    protected $entityManager;

    public function __construct($entityManager=null)
    {
        $this->entityManager = $entityManager;

        parent::__construct('artistic-proposal-form');
        $this->setAttributes([
            'method' => 'POST',
            'class' => 'default-form-actions enable-validators'
        ]);

        $this->add([
            'name' => 'artist_name',
            'options' => [
                'label' => 'Artista/Banda/Companhia'
            ],
            'attributes' => [
                'placeholder' => 'Artista/Banda/Companhia',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'show_name',
            'options' => [
                'label' => 'Nome do show ou espetáculo'
            ],
            'attributes' => [
                'placeholder' => 'Nome do show ou espetáculo',
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'duration',
            'options' => [
                'label' => 'Duração'
            ],
            'attributes' => [
                'placeholder' => 'Duração',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'category',
            'options' => [
                'label' => 'Segmento',
                'value_options' => $this->populateSegments(),
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'target_public',
            'options' => [
                'label' => 'Segmento',
                'value_options' => [
                    'Adulto' => 'Aduto',
                    'Infantil' => 'Infantil',
                    'Não se aplica' => 'Não se aplica'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'suggested_cache',
            'options' => [
                'label' => 'Cache sugerido'
            ],
            'attributes' => [
                'placeholder' => 'Nome do show ou espetáculo',
            ]
        ]);

        $this->add([
            'name' => 'staff_quantity',
            'options' => [
                'label' => 'Equipe',
                'help-block' => 'Nº de pessoas incluindo artistas, banda, técnica, etc'
            ],
            'attributes' => [
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'incentive_law',
            'options' => [
                'label' => 'Projeto de lei de incentivo',
                'value_options' => [
                    'Sim' => 'Sim',
                    'Não' => 'Não'
                ],
                'empty_option' => 'Selecione',
            ],
            'attributes' => [
//                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Textarea',
            'name' => 'links',
            'options' => [
                'label' => 'Links de refêrencia',
                'help-block' => 'Facebook, site, etc'
            ],
            'attributes' => [
                'rows' => 10
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

        //para pesquisa
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

        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'incentive_law' => [
                'name' => 'incentive_law',
                'required' => false,
                'allow_empty' => true
            ],
            'target_public' => [
                'name' => 'target_public',
                'required' => false,
                'allow_empty' => true
            ],
            'category' => [
                'name' => 'category',
                'required' => false,
                'allow_empty' => true
            ]
        ]));

    }

    public function populateSegments()
    {
        $op = [];
        if($this->getEntityManager()) {
            $coll = $this
                ->getEntityManager()
                ->getRepository(ArtisticProposalCategory::class)
                ->findAll();
            foreach ($coll as $c) {
                $op[$c->getId()] = $c->getName();
            }
        }
        return $op;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function setData($data)
    {
        if(!empty($data['category']) && $data['category'] instanceof ArtisticProposalCategory) {
            $cat = $data['category'];
            $data['category'] = $cat->getId();
        }
        return parent::setData($data); // TODO: Change the autogenerated stub
    }

}