<?php
namespace Application\Entity\Programing;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="programing_meta")
 * @ORM\Entity
 */
class Meta extends AbstractEntity
{
    const ADDITIONAL_INFO = 'additional_info';
    const WORLD_PREMIERE = 'world_premiere';
    const NATIONAL_PREMIERE = 'national_premiere';

	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Programing")
     * @ORM\JoinColumn(name="programing_id", referencedColumnName="id")
     */
	private $programing;

    /** @ORM\Column(type="string") */
	private $name;

    /** @ORM\Column(type="string") */
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
    public function getPrograming()
    {
        return $this->programing;
    }

    /**
     * @param mixed $programing
     */
    public function setPrograming($programing)
    {
        $this->programing = $programing;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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