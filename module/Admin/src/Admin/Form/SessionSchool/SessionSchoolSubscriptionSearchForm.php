<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 07/05/2018
 * Time: 15:05
 */

namespace Admin\Form\SessionSchool;

use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\SessionSchool\SessionSchool;
use Zend\Form\Form;

class SessionSchoolSubscriptionSearchForm extends Form
{
    private $entityManager;
    private $event;

    public function __construct($em=null, $event=null)
    {
        if($em) {
            $this->entityManager = $em;
        }

        if($event) {
            $this->event = $event;
        }

        parent::__construct('session-school-subscription-search');
        $this->setAttributes([
            'method' => 'get'
        ]);

        $this->add([
            'name' => 'instituition_social_name',
            'attributes' => [
                'class' => 'input-sm',
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'event',
            'options' => [
                'empty_option' => 'Selecione o evento',
                'value_options' => $this->populateEvents()
            ],
            'attributes' => [
                'class' => 'input-sm',
                'data-label' => 'Evento',
                'id' => 'event'
            ]
        ]);

        $this->add([
            'type' => 'Select',
            'name' => 'session',
            'options' => [
                'empty_option' => 'Selecione uma sessão',
                'value_options' => $this->populateSessions()
            ],
            'attributes' => [
                'class' => 'input-sm'
            ]
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

    public function populateSessions()
    {
        $options = [];
        if($this->event) {
            $sessions = $this
                ->getEntityManager()
                ->getRepository(SessionSchool::class)
                ->findBy([
                    'event' => $this->event
                ]);

            foreach ($sessions as $ses) {
                if(!key_exists($ses->getId(), $options)) {
                    $options[$ses->getId()] = [
                        'label' => $ses->getName(),
                        'options' => []
                    ];
                }

                foreach ($ses->getProgramming() as $pro) {
                    $proTitle = $pro->getDate()->format('d/m/Y')
                        . '|'
                        . $pro->getStartTime()->format('H:i');
                    $options[$ses->getId()]['options'][$pro->getId()] = $proTitle;

                }
            }
        }

        return $options;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

}