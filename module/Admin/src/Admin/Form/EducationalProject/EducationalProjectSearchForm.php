<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/03/2018
 * Time: 09:39
 */
namespace Admin\Form\EducationalProject;

use Admin\Form\EntityManagerTrait;
use Admin\Form\Project\FileFieldset;
use Application\Entity\EducationalProject\Category;
use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Registration\Type;
use Application\Entity\State;
use Application\Entity\User\User;
use Doctrine\ORM\EntityManager;
use Psr\Log\InvalidArgumentException;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class EducationalProjectSearchForm extends Form
{
    use EntityManagerTrait;

    protected $registration;

    public function __construct(EntityManager $em, $registration=null, $disableValidations=false)
    {
        parent::__construct('educational-project-form');
        $this->setAttributes([
            'id' => 'educational-project-form',
            'class' => 'form-horizontal educational-project-form',
            'method' => 'get'
        ]);

        if(!$em) {
            throw new InvalidArgumentException('The entity manager argument is necessary!');
        }
        $this->setEntityManager($em);

        if($registration) {
            if($registration instanceof Registration) {
                $this->setRegistration($registration);
            } elseif(is_numeric($registration)) {
                $this->registration = $this->getRepository(Registration::class)->find($registration);
            } else {
                $this->registration = $this->getRepository(Registration::class)->findOneBy(['hash'=>$registration]);
            }
        }

        $this->add([
            'name' => 'id',
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título do projeto',
            ],
            'attributes' => [
                'class' => 'input-sm',
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
            'attributes' => [
                'data-label' => 'Status'
            ]
        ]);

        $this->add([
            'name' => 'user',
            'options' => [
                'label' => 'Usuário'
            ],
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'event',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => $this->populateEvents()
            ],
            'attributes' => [
                'data-label' => 'Evento',
                'class' => 'input-sm',
                'id' => 'event'
            ]
        ]);

        $this->add([
            'name' => 'dateInit',
            'attributes' => [
                'placeholder' => 'De',
                'class' => 'input-sm',
                'data-inputmask' => "'alias': 'dd/mm/yyyy'"
            ]
        ]);

        $this->add([
            'name' => 'dateEnd',
            'attributes' => [
                'placeholder' => 'Até',
                'class' => 'input-sm',
                'data-inputmask' => "'alias': 'dd/mm/yyyy'"
            ]
        ]);

        $this->add([
            'name' => 'selected',
            'type' => 'hidden'
        ]);

        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'title' => [
                'name'       => 'title',
                'required'   => false,
            ]
        ]));
    }

    /**
     * @return Registration
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

    public function populateCategory()
    {
        $coll = [];
        if($this->getRegistration()) {
            $items = $this
                ->getRepository(Category::class)
                ->findBy(['registration'=>$this->getRegistration()->getId()],['name'=>'ASC']);
        } else {
            $items = $this
                ->getRepository(Category::class)
                ->findBy([],['name'=>'ASC']);
        }
        foreach ($items as $i) {
            $coll[$i->getId()] = $i->getName();
        }
        return $coll;
    }

    public function populateRegulations()
    {
        $regulations = [];
        if($this->getEntityManager()) {
            $coll = $this
                ->getEntityManager()
                ->getRepository(Registration::class)
                ->findBy([
                    'type' => Type::EDUCATIONAL_PROJECT
                ], ['startDate'=>'DESC']);

            foreach ($coll as $c) {
                $regulations[$c->getId()] = $c->getName();
            }
        }
        return $regulations;
    }

    public function populateEvents()
    {
        $options = [];

        if($this->getEntityManager()) {
            $events = $this
                ->getEntityManager()
                ->getRepository(Event::class)
                ->findBy([], ['startDate'=>'DESC']);

            foreach ($events as $p) {
                if(!key_exists($p->getType(), $options)) {
                    $options[$p->getType()] = [
                        'label' => EventType::get($p->getType()),
                        'options' => []
                    ];
                }
                $options[$p->getType()]['options'][$p->getId()] = $p->getShortName();
            }
        }

        return $options;
    }
}