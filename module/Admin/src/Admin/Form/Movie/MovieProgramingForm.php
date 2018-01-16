<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 04/01/2018
 * Time: 10:05
 */

namespace Admin\Form\Movie;

use Admin\Form\Programing\ProgramingForm;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\MovieEventStatus;
use Application\Entity\Programing\Type;
use Zend\InputFilter\Factory as InputFilterFactory;


class MovieProgramingForm extends  ProgramingForm
{
    protected $movie;

    public function __construct($em, $event = null)
    {
        parent::__construct($em, $event);
        $this->setAttributes([
            'class' => 'movie-programing-form',
        ]);

        $this->add([
            'name' => 'type',
            'type' => 'select',
            'options' => [
                'label' => 'Tipo',
                'empty_option' => 'Selecione',
                'value_options' => [
                    Type::MOVIE => 'Filme',
                    Type::SESSION => 'Sessão'
                ],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'id' => 'movie-programing-type'
            ]
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'sessions'
        ]);

        $this->add([
            'name' => 'movie',
            'type' => 'select',
            'options' => [
                'label' => 'Filme',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateMovies(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
                'class' => 'select2'
            ]
        ]);

        $this->add([
            'name' => 'programing_type',
            'type' => 'select',
            'options' => [
                'label' => 'Tipo',
                'empty_option' => 'Tipo',
                'value_options' => [
                    Type::OPENING => 'Abertura',
                    Type::CLOSING => 'Encerramento',
                    Type::ART => 'Arte',
                    Type::MOVIE => 'Filmes',
                    Type::SEMINAR_DEBATE => 'Seminário'

                ],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'sub_event',
            'options' => [
                'label' => 'Sub-mostra',
                'empty_option' => 'Sub-mostra',
                'value_options' => $this->populateSubEvents(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'category',
            'options' => [
                'label' => '',
                'empty_option' => 'Categoria',
                'value_options' => [
                    Movie::CATEGORY_CURTA => 'Curta',
                    Movie::CATEGORY_MEDIA => 'Média',
                    Movie::CATEGORY_LONGA => 'Longa',
                ],
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'meta',
            'type' => MetaProgramingFieldset::class,

        ]);

        $this->get('place')->setAttribute('required', 'required');

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'movie' => [
                'name' => 'movie',
                'required' => false,
                'allow_empry' => true
            ],
            'category' => [
                'name' => 'category',
                'required' => false,
                'allow_empry' => true
            ],
            'programing_type' => [
                'name' => 'programing_type',
                'required' => false,
                'allow_empry' => true
            ]
        ]));
    }

    public function populateSubEvents()
    {
        $subEvents = [];
        if($this->getEvent()) {
            foreach ($this->getEvent()->getSubEvents() as $p) {
                $subEvents[$p->getId()] = $p->getName();
            }
        }

        return $subEvents;
    }

    public function populateMovies()
    {
        $movies = [];
        if($this->getEntityManager() && $this->getEvent()) {
            $itens = $this
                ->getEntityManager()
                ->getRepository(Movie::class)
                ->createQueryBuilder('m')
                ->innerJoin('m.subscriptions', 's')
                ->andWhere('s.event = :idEvent')
                ->andWhere('s.status = :status')
                ->setParameters([
                    'idEvent' => $this->getEvent()->getId(),
                    'status' => MovieEventStatus::SELECTED
                ])
                ->orderBy('m.title', 'ASC')
                ->getQuery()
                ->getResult();

            foreach ($itens as $m) {
                $movies[$m->getId()] = $m->getTitle();
            }
        }
        return $movies;
    }

    public function setData($data)
    {
        if(!empty($data['sub_event']) && is_object($data['sub_event'])) {
            $subEvent = $data['sub_event'];
            $data['sub_event'] = $subEvent->getId();
        }

        if(!empty($data['object']) && is_object($data['object'])) {
            $movie = $data['object'];
            $data['movie'] = $movie->getId();
        }

        if(!empty($data['meta'])) {
            $meta = [];
            foreach ($data['meta'] as $key=>$m) {
                if(is_object($m)) {
                    $meta[$m->getName()] = $m->getValue();
                } else {
                    $meta[$key] = $m;
                }
            }
            $data['meta'] = $meta;
        }

        return parent::setData($data);
    }
}