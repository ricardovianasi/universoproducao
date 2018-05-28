<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:25
 */
namespace Admin\Form\Art;

use Admin\Form\MediaFieldset;
use Admin\Form\Programing\ProgramingFieldset;
use Application\Entity\Art\Category;
use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Zend\Form\Form;

class ArtForm extends Form
{
    private $entityManager;

    public function __construct($em=null)
    {
        $this->entityManager = $em;

        parent::__construct('art-form');
        $this->setAttributes([
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
            'name' => 'file',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'file'
            ],
            'options' => [
                'label' => 'Imagem'
            ]
        ]);

        $this->add([
            'name' => 'programing',
            'type' => 'hidden',
        ]);

        $this->add([
            'type' => 'Collection',
            'name' => 'files',
            'options' => [
                'count' => 1,
                'should_create_template' => false,
                'target_element' => [
                    'type' => MediaFieldset::class
                ]
            ]

        ]);
    }

    public function setData($data)
    {
        if(!empty($data['category']) && is_object($data['category'])) {
            $category = $data['category'];
            $data['category'] = $category->getId();
        }

        if(!empty($data['event']) && is_object($data['event'])) {
            $event = $data['event'];
            $data['event'] = $event->getId();
        }

        $files = [];
        if(isset($data['files'])) {
            if(count($data['files'])) {
                $count = 1;
                foreach ($data['files'] as $key=>$m) {
                    if(is_object($m)) {
                        $files[] = [
                            'id' => $m->getId(),
                            'description' => $m->getDescription(),
                            'src' => $m->getSrc(),
                            'is_default' => $m->getIsDefault()
                        ];
                    } else {
                        $files[] = [
                            'id' => isset($m['id'])?$m['id']:'',
                            'description' => isset($m['description'])?$m['description']:'',
                            'src' => isset($m['src'])?$m['src']:'',
                            'is_default' => isset($m['is_default'])?$m['is_default']:[]
                        ];
                    }
                }
            }
        }
        $data['files'] = $files;

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