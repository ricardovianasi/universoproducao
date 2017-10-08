<?php
namespace Application\Entity\Event;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;
use Application\Annotations as APP;

/**
 * @ORM\Table(name="event_place")
 * @ORM\Entity(repositoryClass="Application\Repository\Event\Place")
 */
class Place extends AbstractEntity
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

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /** @ORM\Column(name="`event_type`", type="string") */
    private $eventType;

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
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param mixed $eventType
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }
}