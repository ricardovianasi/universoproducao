<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 03/05/2018
 * Time: 13:41
 */

namespace Application\Entity\SessionSchool;

use Application\Entity\Programing\Programing;
use Application\Entity\Registration\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="session_school")
 * @ORM\Entity(repositoryClass="Application\Repository\SessionSchool\SessionSchool")
 * @ORM\HasLifecycleCallbacks()
 */
class SessionSchool extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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

    /** @ORM\Column(name="name", type="string", nullable=true) */
    private $name;

    /** @ORM\Column(name="age_range", type="string", nullable=true) */
    private $ageRange;

    /**
     * @ORM\OneToMany(targetEntity="SessionSchoolMovies", mappedBy="session", cascade={"ALL"})
     */
    private $movies;

    private $programming;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
        $this->programming = new ArrayCollection();
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
    public function getAgeRange()
    {
        return $this->ageRange;
    }

    /**
     * @param mixed $ageRange
     */
    public function setAgeRange($ageRange)
    {
        $this->ageRange = $ageRange;
    }

    /**
     * @return ArrayCollection
     */
    public function getMovies()
    {
        return $this->movies;
    }

    /**
     * @param mixed $movies
     */
    public function setMovies($movies)
    {
        $this->movies = $movies;
    }

    /**
     * @return mixed
     */
    public function getProgramming()
    {
        return $this->programming;
    }

    /**
     * @ORM\PostLoad
     * @ORM\PostPersist
     */
    public function setProgramming(LifecycleEventArgs $event)
    {
        $this->programming = $event
            ->getEntityManager()
            ->getRepository(Programing::class)
            ->findBy([
                'type' => Type::SESSION_SCHOOL,
                'objectId' => $this->id
            ]);
    }
}