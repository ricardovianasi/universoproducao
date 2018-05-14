<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:25
 */
namespace Admin\Form\Seminar;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Seminar\Category;
use Application\Entity\Seminar\Thematic;
use Zend\Form\Form;

class SeminarDebateForm extends Form
{
    private $entityManager;

    public function __construct($em=null)
    {
        $this->entityManager = $em;

        parent::__construct('seminar-debate-form');
        $this->setAttributes([
            'methor' => 'POST',
            'class' => 'default-form-actions enable-validators'
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'event',
            'options' => [
                'label' => 'Evento',
                'empty_option' => 'Selecione o evento',
                'value_options' => $this->populateEvents(),

            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'thematic',
            'options' => [
                'label' => 'Temática',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateThematic(),

            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'category',
            'options' => [
                'label' => 'Categoria',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateCategories(),

            ],
            'attributes' => [
                'required' => 'required',
            ]
        ]);

        $this->add([
            'name' => 'title',
            'options' => [
                'label' => 'Título',

            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        $this->add([
            'name' => 'description',
            'options' => [
                'label' => 'Descrição',

            ],
            'attributes' => [
                'class' => 'tinymce_minimal'
            ]
        ]);

        $this->add([
            'name' => 'programing',
            'type' => 'hidden',
        ]);
    }

    public function setData($data)
    {
        if(!empty($data['thematic']) && is_object($data['thematic'])) {
            $thematic = $data['thematic'];
            $data['thematic'] = $thematic->getId();
        }

        if(!empty($data['category']) && is_object($data['category'])) {
            $category = $data['category'];
            $data['category'] = $category->getId();
        }

        if(!empty($data['event']) && is_object($data['event'])) {
            $event = $data['event'];
            $data['event'] = $event->getId();
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
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

    public function populateThematic()
    {
        $options = [];
        if($this->getEntityManager()) {
            $ops = $this
                ->getEntityManager()
                ->getRepository(Thematic::class)
                ->findBy([], ['name'=>'Desc']);

            foreach ($ops as $o) {
                $options[$o->getId()] = $o->getName();
            }
        }
        return $options;
    }

    public function populateCategories()
    {
        $options = [];
        if($this->getEntityManager()) {
            $ops = $this
                ->getEntityManager()
                ->getRepository(Category::class)
                ->findBy([], ['name'=>'Desc']);

            foreach ($ops as $o) {
                $options[$o->getId()] = $o->getName();
            }
        }
        return $options;
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
}