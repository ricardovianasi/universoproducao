<?php
namespace Application\Entity\Workshop;

use Application\Entity\Event\Event;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="workshop")
 * @ORM\Entity(repositoryClass="Application\Repository\Workshop\Workshop")
 * @ORM\HasLifecycleCallbacks()
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

    /** @ORM\Column(name="duration", type="string", nullable=true) */
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

    /** @ORM\Column(name="file", type="string", nullable=true) */
    private $file;

    /** @ORM\OneToMany(targetEntity="WorkshopSubscription", mappedBy="workshop", fetch="EXTRA_LAZY") */
    private $subscriptions;

    private $programing;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
        $this->programing = new ArrayCollection();
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
     * @return \Application\Entity\Registration\Registration
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

    /**
     * @return Event
     */
    public function getEvent()
    {
        if(!$this->getRegistration()) {
            return null;
        }

        $events = $this->getRegistration()->getEvents();
        if(!count($events)) {
            return null;
        }

        return $events[0];
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param ArrayCollection $subscriptions
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    public function hasAvailableSubscriptions()
    {
        $maxSubscriptions = $this->getMaximumSubscriptions()
            ? $this->getMaximumSubscriptions()
            : $this->getAvailableSubscriptions();

        if($this->getSubscriptions()->count() >= $maxSubscriptions) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getPrograming()
    {
        return $this->programing;
    }

    /**
     * @ORM\PostLoad
     * @ORM\PostPersist
     */
    public function setPrograming(LifecycleEventArgs $event)
    {
        $this->programing = $event
            ->getEntityManager()
            ->getRepository(Programing::class)
            ->findBy([
                'type' => Type::WORKSHOP,
                'objectId' => $this->id
            ]);
    }

}