<?php
namespace Application\Entity\Workshop;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
* @ORM\Table(name="workshop_pontuation_itens")
* @ORM\Entity
*/
class PontuationItens extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
}