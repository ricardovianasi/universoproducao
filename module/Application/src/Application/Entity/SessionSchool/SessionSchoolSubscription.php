<?php
namespace Application\Entity\SessionSchool;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="session_school_subscription")
 * @ORM\Entity
 */
class SessionSchoolSubscription extends AbstractEntity
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
     * @ORM\OneToOne(targetEntity="Application\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Registration\Registration")
     * @ORM\JoinColumn(name="registration_id", referencedColumnName="id")
     */
    private $registration;

    /**
     * @ORM\OneToOne(targetEntity="SessionSchool")
     * @ORM\JoinColumn(name="session_school_id", referencedColumnName="id")
     */
    private $session;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Programing\Programing")
     * @ORM\JoinColumn(name="session_school_prog", referencedColumnName="id")
     */
    private $sessionProgramming;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Institution\Institution", cascade={"ALL"})
     * @ORM\JoinColumn(name="instituition_id", referencedColumnName="id")
     */
    private $instituition;

    /** @ORM\Column(name="instituition_direction", type="string", nullable=true) */
    private $instituitionDirection;

    /** @ORM\Column(name="instituition_direction_email", type="string", nullable=true) */
    private $instituitionDirectionEmail;

    /** @ORM\Column(name="instituition_direction_phone", type="string", nullable=true) */
    private $instituition_direction_phone;

    /** @ORM\Column(name="responsible", type="string", nullable=true) */
    private $responsible;

    /** @ORM\Column(name="responsible_office", type="string", nullable=true) */
    private $responsibleOffice;

    /** @ORM\Column(name="responsible_phone", type="string", nullable=true) */
    private $responsiblePhone;

    /** @ORM\Column(name="responsible_mobile_phone", type="string", nullable=true) */
    private $responsibleMobilePhone;

    /** @ORM\Column(name="participants", type="integer", nullable=true) */
    private $participants;

    /** @ORM\Column(name="series_age", type="integer", nullable=true) */
    private $seriesAge;

    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private $updatedAt;

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
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return mixed
     */
    public function getInstituition()
    {
        return $this->instituition;
    }

    /**
     * @param mixed $instituition
     */
    public function setInstituition($instituition)
    {
        $this->instituition = $instituition;
    }

    /**
     * @return mixed
     */
    public function getInstituitionDirection()
    {
        return $this->instituitionDirection;
    }

    /**
     * @param mixed $instituitionDirection
     */
    public function setInstituitionDirection($instituitionDirection)
    {
        $this->instituitionDirection = $instituitionDirection;
    }

    /**
     * @return mixed
     */
    public function getInstituitionDirectionEmail()
    {
        return $this->instituitionDirectionEmail;
    }

    /**
     * @param mixed $instituitionDirectionEmail
     */
    public function setInstituitionDirectionEmail($instituitionDirectionEmail)
    {
        $this->instituitionDirectionEmail = $instituitionDirectionEmail;
    }

    /**
     * @return mixed
     */
    public function getInstituitionDirectionPhone()
    {
        return $this->instituition_direction_phone;
    }

    /**
     * @param mixed $instituition_direction_phone
     */
    public function setInstituitionDirectionPhone($instituition_direction_phone)
    {
        $this->instituition_direction_phone = $instituition_direction_phone;
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
    public function getResponsibleOffice()
    {
        return $this->responsibleOffice;
    }

    /**
     * @param mixed $responsibleOffice
     */
    public function setResponsibleOffice($responsibleOffice)
    {
        $this->responsibleOffice = $responsibleOffice;
    }

    /**
     * @return mixed
     */
    public function getResponsiblePhone()
    {
        return $this->responsiblePhone;
    }

    /**
     * @param mixed $responsiblePhone
     */
    public function setResponsiblePhone($responsiblePhone)
    {
        $this->responsiblePhone = $responsiblePhone;
    }

    /**
     * @return mixed
     */
    public function getResponsibleMobilePhone()
    {
        return $this->responsibleMobilePhone;
    }

    /**
     * @param mixed $responsibleMobilePhone
     */
    public function setResponsibleMobilePhone($responsibleMobilePhone)
    {
        $this->responsibleMobilePhone = $responsibleMobilePhone;
    }

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    /**
     * @return mixed
     */
    public function getSeriesAge()
    {
        return $this->seriesAge;
    }

    /**
     * @param mixed $seriesAge
     */
    public function setSeriesAge($seriesAge)
    {
        $this->seriesAge = $seriesAge;
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

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getSessionProgramming()
    {
        return $this->sessionProgramming;
    }

    /**
     * @param mixed $sessionProgramming
     */
    public function setSessionProgramming($sessionProgramming)
    {
        $this->sessionProgramming = $sessionProgramming;
    }
}