<?php
namespace Application\Entity\Workshop;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
* @ORM\Table(name="workshop_pontuation_itens")
* @ORM\Entity
*/
class PontuationItems extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pontuation", inversedBy="items")
     * @ORM\JoinColumn(name="workshop_pontuation_id", referencedColumnName="id")
     */
    private $pontuation;

    /** @ORM\Column(type="text", nullable=true) */
    private $description;

    /** @ORM\Column(type="text", nullable=true) */
    private $value;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPontuation()
    {
        return $this->pontuation;
    }

    /**
     * @param mixed $pontuation
     */
    public function setPontuation($pontuation)
    {
        $this->pontuation = $pontuation;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}