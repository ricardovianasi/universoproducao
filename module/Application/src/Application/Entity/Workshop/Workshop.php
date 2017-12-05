<?php
namespace Application\Entity\Workshop;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="workshop")
 * @ORM\Entity(repositoryClass="Application\Repository\Workshop\Workshop")
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
     * @ORM\OneToOne(targetEntity="Application\Entity\Registration\Registration")
     * @ORM\JoinColumn(name="registration_id", referencedColumnName="id")
     */
    private $registration;

    /**
     * @ORM\ManyToOne(targetEntity="Manager")
     * @ORM\JoinColumn(name="workshop_manager_id", referencedColumnName="id")
     */
    private $manager;

    /** @ORM\Column(name="minimum_age", type="integer", nullable=true) */
    private $minimumAge;

    /** @ORM\Column(name="maximum_age", type="integer", nullable=true) */
    private $maximumAge;

    /** @ORM\Column(name="duration", type="time", nullable=true) */
    private $duration;

    /** @ORM\Column(name="available_subscriptions", type="integer", nullable=true) */
    private $availableSubscriptions;

    /** @ORM\Column(name="maximum_subscriptions", type="integer", nullable=true) */
    private $maximumSubscriptions;

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
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param mixed $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
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
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        if(is_string($duration)) {
            $duration = \DateTime::createFromFormat('H:i:s', $duration);
        }
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getAvailableSubscriptions()
    {
        return $this->availableSubscriptions;
    }

    /**
     * @param mixed $availableSubscriptions
     */
    public function setAvailableSubscriptions($availableSubscriptions)
    {
        $this->availableSubscriptions = $availableSubscriptions;
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

    /**
     * @return mixed
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    public function setRegistration($reg)
    {
        $this->registration = $reg;
    }

    /**
     * @return mixed
     */
    public function getMaximumSubscriptions()
    {
        return $this->maximumSubscriptions;
    }

    /**
     * @param mixed $maximumSubscriptions
     */
    public function setMaximumSubscriptions($maximumSubscriptions)
    {
        $this->maximumSubscriptions = $maximumSubscriptions;
    }
}