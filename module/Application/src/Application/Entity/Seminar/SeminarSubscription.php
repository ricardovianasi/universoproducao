<?php
namespace Application\Entity\Seminar;

use Admin\View\Helper\RegistrationStatus;
use Application\Entity\Event\Event;
use Application\Entity\Registration\Registration;
use Application\Entity\Registration\Status;
use Application\Entity\Seminar\Category;
use Application\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="seminar_subscription")
 * @ORM\Entity(repositoryClass="Application\Repository\Seminar\SeminarSubscription")
 */
class SeminarSubscription extends AbstractEntity
{
	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\User\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
	private $user;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Registration\Registration")
     * @ORM\JoinColumn(name="registration_id", referencedColumnName="id")
     */
    private $registration;


    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @ORM\ManyToMany(targetEntity="Debate")
     * @ORM\JoinTable(name="seminar_subscription_has_debate",
     *      joinColumns={@ORM\JoinColumn(name="seminar_subscription_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="seminar_debate_id", referencedColumnName="id")}
     *      )
     */
    private $debates;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Seminar\Category")
     * @ORM\JoinColumn(name="seminar_category_id", referencedColumnName="id")
     */
    private $seminarCategory;

    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private $createdAt;

    public function __construct()
    {
        $this->debates = new ArrayCollection();
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param mixed $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    /**
     * @return Event
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
     * @return Category
     */
    public function getSeminarCategory()
    {
        return $this->seminarCategory;
    }

    /**
     * @param mixed $seminarCategory
     */
    public function setSeminarCategory($seminarCategory)
    {
        $this->seminarCategory = $seminarCategory;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getDebates()
    {
        return $this->debates;
    }

    /**
     * @param mixed $debates
     */
    public function setDebates($debates)
    {
        $this->debates = $debates;
    }
}