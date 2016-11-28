<?php
namespace Application\Entity\Seminar;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="seminar")
 * @ORM\Entity
 */
class Seminar extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="name", type="string", nullable=true) */
    private $name;

    /** @ORM\Column(name="description", type="text", nullable=true) */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Thematic")
     * @ORM\JoinColumn(name="seminar_thematic_id", referencedColumnName="id")
     */
    private $thematic;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

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
    public function getThematic()
    {
        return $this->thematic;
    }

    /**
     * @param mixed $thematic
     */
    public function setThematic($thematic)
    {
        $this->thematic = $thematic;
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
}