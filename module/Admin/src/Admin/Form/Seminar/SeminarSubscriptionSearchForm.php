<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 16/05/2018
 * Time: 15:53
 */

namespace Admin\Form\Seminar;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Seminar\Category;
use Zend\Form\Form;

class SeminarSubscriptionSearchForm extends Form
{
    private $entityManager;

    public function __construct($entityManager=null)
    {
        if($entityManager) {
            $this->entityManager = $entityManager;
        }

        parent::__construct('seminar-subscription-search');
        $this->setAttributes([
            'method' => 'get'
        ]);

        $this->add([
            'name' => 'id',
            'attributes' => [
                'class' => 'input-sm',
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
            'type' => 'Select',
            'name' => 'category',
            'options' => [
                'empty_option' => 'Selecione',
                'value_options' => $this->populateCategories()
            ],
            'attributes' => [
                'class' => 'input-sm',
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
            $items = $this
                ->getEntityManager()
                ->getRepository(Category::class)
                ->findAll();

            foreach ($items as $i) {
                $options[$i->getId()] = $i->getName();
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