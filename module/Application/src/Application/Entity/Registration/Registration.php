<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 23/09/2017
 * Time: 12:43
 */

namespace Application\Entity\Registration;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;
use Util\Security\Crypt;

/**
 * @ORM\Table(name="registration")
 * @ORM\Entity(repositoryClass="Application\Repository\Registration\Registration")
 */
class Registration extends AbstractEntity
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="name", type="string", nullable=true) */
    private $name;

    /** @ORM\Column(name="start_date", type="datetime", nullable=true) */
    private $startDate;

    /** @ORM\Column(name="end_date", type="datetime", nullable=true) */
    private $endDate;

    /** @ORM\Column(name="`type`", type="string", nullable=true) */
    private $type;

    /** @ORM\Column(name="`status`", type="string", nullable=true) */
    private $status;

    /** @ORM\Column(name="regulation", type="text", nullable=true) */
    private $regulation;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Event\Event", cascade={"ALL"})
     * @ORM\JoinTable(name="registration_has_events",
     *      joinColumns={@ORM\JoinColumn(name="registration_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $events;

    /** @ORM\Column(name="`position`", type="string", nullable=true) */
    private $position;

    /** @ORM\Column(name="`cover`", type="string", nullable=true) */
    private $cover;

    /** @ORM\Column(name="`info`", type="string", nullable=true) */
    private $info;

    /** @ORM\Column(name="`hash`", type="string", nullable=true) */
    private $hash;

    /**
     * @ORM\OneToMany(targetEntity="Options", mappedBy="registration", cascade={"ALL"})
     */
    private $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->hash = Crypt::makePassword(80, true, true, "-_.");
    }

    public function getOption($option)
    {
        foreach ($this->options as $op) {
            if($op->getName() == $option) {
                return $op;
            }
        }

        return false;
    }

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
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->parseData($startDate, $this->startDate);
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->parseData($endDate, $this->endDate);
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

    public function getTypeName()
    {
        return Type::get($this->getType());
    }

    /**
     * @return mixed
     */
    public function getRegulation()
    {
        return $this->regulation;
    }

    /**
     * @param mixed $regulation
     */
    public function setRegulation($regulation)
    {
        $this->regulation = $regulation;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param ArrayCollection $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param mixed $cover
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function isOpen()
    {
        if($this->status != self::STATUS_ENABLED) {
            return false;
        }

        $now = new \DateTime();

        if($this->getEndDate()) {
            if($this->getStartDate() <= $now && $this->endDate >= $now) {
                return true;
            } else {
                return false;
            }
        } else {
            if($this->getStartDate() >= $now) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * @return ArrayCollection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param ArrayCollection $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }
}