<?php
namespace Application\Entity\Movie;

use Admin\View\Helper\RegistrationStatus;
use Application\Entity\Event\Event;
use Application\Entity\Registration\Status;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="movie_events")
 * @ORM\Entity
 */
class MovieSubscription extends AbstractEntity
{
	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="events")
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     */
    private $movie;

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

    /** @ORM\Column(name="status", type="string", nullable=true) */
    private $status = MovieEventStatus::ON_EVALUATION;

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
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * @param mixed $movie
     */
    public function setMovie($movie)
    {
        $this->movie = $movie;
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

    public function _toArray()
    {
        return [
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'status_name' => Status::get($this->getStatus()),
            'event_id' => $this->getEvent()->getId(),
            'event_short_name' => $this->getEvent()->getShortName(),
            'event_full_name' => $this->getEvent()->getFullName()
        ];
    }
}