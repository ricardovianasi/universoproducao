<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/01/2018
 * Time: 13:25
 */
namespace Admin\Form\Programing;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Programing\Type;
use Zend\Form\Form;

class GenericItemForm extends Form
{
    private $entityManager;

    public function __construct($em=null)
    {
        $this->entityManager = $em;

        parent::__construct('generic-item-form');
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
            'name' => 'type',
            'options' => [
                'label' => 'Tipo',
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

    public function populateCategories()
    {
        return [
            Type::OPENING => 'Abertura',
            Type::CLOSING => 'Encerramento',
            Type::OTHER => 'Outros'
        ];
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