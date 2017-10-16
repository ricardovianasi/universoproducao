<?php
namespace Admin\Form\Movie;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Movie\Category;
use Application\Entity\Registration\Status;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class MovieFormSearch extends Form
{
    protected $entityManager;

    public function __construct($entityManager)
    {

        $this->entityManager = $entityManager;

        parent::__construct('movie-form-search');
        $this->setAttributes([
            'method' => 'GET',
        ]);

        $this->add([
            'name' => 'id',
            'attributes' => [
                'class' => 'input-sm'
            ]
        ]);

        $this->add([
            'name' => 'title',
            'attributes' => [
                'class' => 'input-sm'
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
            'name' => 'author',
            'attributes' => [
                'class' => 'input-sm'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'events',
            'options' => [
                'empty_option' => 'Selecione o evento',
                'value_options' => $this->populateEvents()
            ],
            'attributes' => [
                'class' => 'input-sm'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'status',
            'options' => [
                'empty_option' => 'Selecione o status',
                'value_options' => Status::toArray()
            ],
            'attributes' => [
                'class' => 'input-sm'
            ]
        ]);

        /*$this->add([
            'type' => 'Select',
            'name' => 'category',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => Category::toArray()
            ],
            'attributes' => [
                'class' => 'input-sm'
            ]
        ]);*/

        $this->add([
            'name' => 'durationInit',
            'attributes' => [
                'placeholder' => 'De',
                'class' => 'input-sm',
                'data-inputmask' => "'alias': 'hh:mm:ss'"
            ]
        ]);

        $this->add([
            'name' => 'durationEnd',
            'attributes' => [
                'placeholder' => 'Até',
                'class' => 'input-sm',
                'data-inputmask' => "'alias': 'hh:mm:ss'"
            ]
        ]);

        //Validações
        $this->setInputFilter((new InputFilterFactory)->createInputFilter([
            'events' => [
                'name' => 'events',
                'required'   => false,
                'allow_empty' => true
            ],
            'status' => [
                'name'=> 'status',
                'required'   => false,
                'allow_empty' => true
            ],
            'category' => [
                'name'=> 'category',
                'required'   => false,
                'allow_empty' => true
            ],
        ]));

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


    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function setData($data)
    {
        if(!empty($data['dateInit']) && $data['dateInit'] instanceof \DateTime) {
            $dateInit = $data['dateInit'];
            $data['dateInit'] = $dateInit->format('d/m/Y');
        }

        if(!empty($data['dateEnd']) && $data['dateEnd'] instanceof \DateTime) {
            $dateEnd = $data['dateEnd'];
            $data['dateEnd'] = $dateEnd->format('d/m/Y');
        }

        return parent::setData($data); // TODO: Change the autogenerated stub
    }
}
