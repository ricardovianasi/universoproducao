<?php
namespace Application\Entity\Workshop;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="workshop")
 * @ORM\Entity
 */
class Workshop extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="name", type="string", nullable=true) */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    private $responsible;

    /** @ORM\Column(name="minimum_age", type="integer", nullable=true) */
    private $minimumAge;

    /** @ORM\Column(name="maximum_age", type="integer", nullable=true) */
    private $maximumAge;

    /** @ORM\Column(name="hours", type="integer", nullable=true) */
    private $hours;

    /** @ORM\Column(name="number_places", type="integer", nullable=true) */
    private $numberPlaces;

    /** @ORM\Column(name="description", type="text", nullable=true) */
    private $description;

    /** @ORM\Column(name="requirements", type="text", nullable=true) */
    private $requirements;

    /** @ORM\Column(name="program", type="text", nullable=true) */
    private $program;

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
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getResponsible()
    {
        return $this->responsible;
    }

    /**
     * @param mixed $responsible
     */
    public function setResponsible($responsible)
    {
        $this->responsible = $responsible;
    }

    /**
     * @return mixed
     */
    public function getMinimumAge()
    {
        return $this->minimumAge;
    }

    /**
     * @param mixed $minimumAge
     */
    public function setMinimumAge($minimumAge)
    {
        $this->minimumAge = $minimumAge;
    }

    /**
     * @return mixed
     */
    public function getMaximumAge()
    {
        return $this->maximumAge;
    }

    /**
     * @param mixed $maximumAge
     */
    public function setMaximumAge($maximumAge)
    {
        $this->maximumAge = $maximumAge;
    }

    /**
     * @return mixed
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @param mixed $hours
     */
    public function setHours($hours)
    {
        $this->hours = $hours;
    }

    /**
     * @return mixed
     */
    public function getNumberPlaces()
    {
        return $this->numberPlaces;
    }

    /**
     * @param mixed $numberPlaces
     */
    public function setNumberPlaces($numberPlaces)
    {
        $this->numberPlaces = $numberPlaces;
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
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @param mixed $requirements
     */
    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;
    }

    /**
     * @return mixed
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * @param mixed $program
     */
    public function setProgram($program)
    {
        $this->program = $program;
    }
}