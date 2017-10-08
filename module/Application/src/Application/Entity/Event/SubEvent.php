<?php
namespace Application\Entity\Event;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;
use Application\Annotations as APP;

/**
 * @ORM\Table(name="sub_event")
 * @ORM\Entity(repositoryClass="Application\Repository\Event\SubEvent")
 */
class SubEvent extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/** @ORM\Column(name="`name`", type="string") */
	private $name;

	/** @ORM\Column(name="description", type="text") */
	private $description;

    /** @ORM\Column(name="`type`", type="string") */
	private $type;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}