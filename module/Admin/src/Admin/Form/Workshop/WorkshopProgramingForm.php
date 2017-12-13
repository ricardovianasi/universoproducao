<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 11/12/2017
 * Time: 10:13
 */

namespace Admin\Form\Workshop;


use Admin\Form\Programing\ProgramingForm;
use Application\Entity\Registration\Type;
use Application\Entity\Workshop\Workshop;

class WorkshopProgramingForm extends ProgramingForm
{

    public function __construct($em, $event=null)
    {
        parent::__construct($em, $event);

        $this->add([
            'name' => 'workshop',
            'type' => 'select',
            'options' => [
                'label' => 'Oficina',
                'empty_option' => 'Selecione',
                'value_options' => $this->populateWorkshop(),
                'twb-layout' => 'horizontal',
                'column-size' => 'md-4',
                'label_attributes' => [
                    'class' => 'col-md-4'
                ]
            ],
            'attributes' => [
                'class' => 'input-sm'
            ]
        ]);
    }

    public function populateWorkshop()
    {
        $workshop = [];
        if($this->getEvent()) {
            $qb = $this
                ->getEntityManager()
                ->getRepository(Workshop::class)
                ->createQueryBuilder('w')
                ->innerJoin('w.registration', 'r')
                ->innerJoin('r.events', 'e')
                ->andWhere('e.id = :eventId')
                ->andWhere('r.type = :registrationType')
                ->setParameters([
                    'eventId' => $this->getEvent()->getId(),
                    'registrationType' => Type::WORKSHOP
                ]);

            $wShop = $qb->getQuery()->getResult();
            foreach ($wShop as $w) {
                $workshop[$w->getId()] = $w->getName();
            }
        }

        return $workshop;
    }
}