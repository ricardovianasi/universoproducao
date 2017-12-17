<?php
namespace Application\Entity\Workshop;

use Admin\View\Helper\RegistrationStatus;
use Application\Entity\Event\Event;
use Application\Entity\Registration\Status;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="workshop_subscription")
 * @ORM\Entity
 */
class WorkshopSubscription extends AbstractEntity
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
     * @ORM\ManyToOne(targetEntity="Workshop", inversedBy="events")
     * @ORM\JoinColumn(name="workshop_id", referencedColumnName="id")
     */
    private $workshop;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /** @ORM\Column(name="status", type="string", nullable=true) */
    private $status = Status::ON_EVALUATION;

    /** @ORM\OneToMany(targetEntity="WorkshopSubscriptionAnswerForm", mappedBy="subscription", cascade={"ALL"}) */
    private $formAnswers;

    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private $createdAt;

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
     * @return mixed
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }

    /**
     * @param mixed $workshop
     */
    public function setWorkshop($workshop)
    {
        $this->workshop = $workshop;
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
     * @return mixed
     */
    public function getFormAnswers()
    {
        return $this->formAnswers;
    }

    /**
     * @param mixed $formAnswers
     */
    public function setFormAnswers($formAnswers)
    {
        $this->formAnswers = $formAnswers;
    }

    /**
     * @return mixed
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
}