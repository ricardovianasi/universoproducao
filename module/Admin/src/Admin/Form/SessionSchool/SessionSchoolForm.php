<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 03/05/2018
 * Time: 09:37
 */

namespace Admin\Form\SessionSchool;


use Admin\Form\Programing\ProgramingFieldset;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\MovieEventStatus;
use Application\Entity\Programing\Meta;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Feed\Reader\Collection;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class SessionSchoolForm extends Form
{
    private $entityManager;
    private $registration;

    public function __construct($em, $registration=null)
    {
        if ($em) {
            $this->entityManager = $em;
        }

        if ($registration) {
            $this->registration = $registration;
        }

        parent::__construct('session-school-form');
        $this->setAttributes([
            'method' => 'POST',
            'class' => ' form-reload default-form-actions enable-validators nestable-serialize-form'
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'registration',
            'options' => [
                'label' => 'Regulamento',
                'value_options' => $this->populateRegulations(),
                'empty_option' => 'Selecione'
            ],
            'attributes' => [
                'required' => 'required',
                'class' => 'trigger-form-reload'
            ]
        ]);

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Nome',
            ],
            'attributes' => [
                'placeholder' => 'Nome da sessão',
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'age_range',
            'options' => [
                'label' => 'Faixa etária',
            ],
        ]);

        $this->add([
            'type' => 'number',
            'name' => 'order',
            'options' => [
                'label' => 'Posição',
            ],
        ]);

        $this->add([
            'name' => 'movie',
            'type' => 'select',
            'options' => [
                'label' => 'Filme(s)',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateMovies(),
            ],
            'attributes' => [
                'class' => 'select2 nestable-serialize-input',
            ]
        ]);

        $this->add([
            'type' => 'hidden',
            'name' => 'movies',
            'attributes' => [
                'class' => 'nestable-serialize-serialized',
            ]
        ]);

        $programmingFieldset = new ProgramingFieldset("", [], $this->entityManager);
        $this->add([
            'type' => 'Collection',
            'name' => 'programming',
            'options' => [
                'label' => 'Programação',
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => $programmingFieldset
            ]
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'movie' => [
                'name' => 'movie',
                'required' => false,
                'allow_empty' => true
            ],
            'order' => [
                'name' => 'order',
                'required' => false
            ]
        ]));
    }

    public function populateRegulations()
    {
        $regulations = [];
        if($this->getEntityManager()) {
            $coll = $this
                ->getEntityManager()
                ->getRepository(Registration::class)
                ->findBy([
                    'type' => Type::SESSION_SCHOOL
                ], ['startDate'=>'DESC']);

            foreach ($coll as $c) {
                $regulations[$c->getId()] = $c->getName();
            }
        }
        return $regulations;
    }

    public function setData($data)
    {
        if(!empty($data['registration']) && $data['registration'] instanceof Registration) {
            $reg = $data['registration'];
            $data['registration'] = $reg->getId();
        }

        if(!empty($data['movies']) && !is_string($data['movies'])) {
            unset($data['movies']);
        }

        unset($data['movie']);

        if(!empty($data['programming'])) {
            $progs = [];
            $programming = $data['programming'];
            foreach ($programming as $pro) {
                if($pro instanceof Programing) {
                    $progs[] = [
                        'id' => $pro->getId(),
                        'date' => $pro->getDate()?$pro->getDate()->format('d/m/Y'):'',
                        'start_time' => $pro->getStartTime()?$pro->getStartTime()->format('H:i:s'):'',
                        'end_time' => $pro->getEndTime()?$pro->getEndTime()->format('H:i:s'):'',
                        'place' => $pro->getPlace() ? $pro->getPlace()->getId() : '',
                        'available_places' => $pro->getAvailablePlaces(),
                        Meta::ADDITIONAL_INFO => $pro->hasMeta(Meta::ADDITIONAL_INFO)?$pro->hasMeta(Meta::ADDITIONAL_INFO)->getValue():""

                    ];
                } else {
                    $progs[] = $pro;
                }
            }
            $data['programming'] = $progs;

        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }

    public function populateMovies()
    {
        $movies = [];
        if($this->getEntityManager() && $this->getRegistration()) {
            $itens = $this
                ->getEntityManager()
                ->getRepository(Movie::class)
                ->createQueryBuilder('m')
                ->innerJoin('m.subscriptions', 's')
                ->andWhere('s.event = :idEvent')
                ->andWhere('s.status = :status')
                ->setParameters([
                    'idEvent' => $this->getRegistration()->getEvent()->getId(),
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

    /**
     * @return null
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param null $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param null $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

}