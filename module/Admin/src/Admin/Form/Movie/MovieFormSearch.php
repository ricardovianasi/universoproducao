<?php
namespace Admin\Form\Movie;

use Application\Entity\Event\Event;
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
            'method' => 'POST',
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
                'class' => 'input-sm'
            ]
        ]);

        $this->add([
            'name' => 'dateEnd',
            'attributes' => [
                'placeholder' => 'Até',
                'class' => 'input-sm'
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

        $this->add([
            'type' => 'Select',
            'name' => 'category',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => Category::toArray()
            ],
            'attributes' => [
                'class' => 'input-sm'
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
        $eve = [];
        $events = $this
            ->getEntityManager()
            ->getRepository(Event::class);

        foreach ($events as $e) {
            $eve[$e->getId()] = $e->getShortName();
        }

        return $eve;
    }


    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
