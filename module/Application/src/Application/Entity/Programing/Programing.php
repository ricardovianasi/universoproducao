<?php
namespace Application\Entity\Programing;

use Application\Entity\Art\Art;
use Application\Entity\Event\Place;
use Application\Entity\Movie\Movie;
use Application\Entity\Seminar\Debate;
use Application\Entity\SessionSchool\SessionSchool;
use Application\Entity\Workshop\Workshop;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="programing")
 * @ORM\Entity(repositoryClass="Application\Repository\Programing\Programing")
 * @ORM\HasLifecycleCallbacks()
 */
class Programing extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
	private $event;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Event\SubEvent")
     * @ORM\JoinColumn(name="sub_event_id", referencedColumnName="id")
     */
    private $subEvent;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Event\Place")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     */
	private $place;

    /** @ORM\Column(name="`date`", type="date") */
	private $date;

    /** @ORM\Column(name="`start_time`", type="time") */
	private $startTime;

    /** @ORM\Column(name="`end_time`", type="time") */
	private $endTime;

    /** @ORM\Column(name="`type`", type="string") */
	private $type;

    /** @ORM\Column(name="`object_id`", type="string") */
	private $objectId;

	private $object;

    /** @ORM\OneToMany(targetEntity="Meta", mappedBy="programing", cascade={"ALL"}) */
	private $meta;

    /**
     * @ORM\ManyToOne(targetEntity="Programing", inversedBy="children")
     * @ORM\JoinColumn(name="parent_programing_id", referencedColumnName="id")
     */
	private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Programing", mappedBy="parent", cascade={"ALL"})
     * @ORM\OrderBy({"order" = "ASC"})
     */
	private $children;

    /** @ORM\Column(name="`order`", type="integer") */
	private $order;

    /** @ORM\Column(name="`age_range`", type="string") */
	private $ageRange;

    /** @ORM\Column(name="`available_places`", type="string") */
    private $availablePlaces;

    /** @ORM\Column(name="`title`", type="text") */
    private $title;

	public function __construct()
    {
        $this->meta = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * @return Place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->parseData($date, $this->date);
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime)
    {
        if(empty($startTime)) {
            $startTime = null;
        } elseif(is_string($startTime)) {
            $startTime = \DateTime::createFromFormat('H:i:s', $startTime);
        }
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param mixed $endTime
     */
    public function setEndTime($endTime)
    {
        if(empty($endTime)) {
            $endTime = null;
        } elseif(is_string($endTime)) {
            $endTime = \DateTime::createFromFormat('H:i:s', $endTime);
        }
        $this->endTime = $endTime;
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

    /**
     * @return mixed
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param mixed $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    public function getObject()
    {
        return $this->object;
    }

    /**
     * @ORM\PostLoad
     * @ORM\PostPersist
     */
    public function setObject(LifecycleEventArgs $event)
    {
        if(!($this->type && $this->objectId)) {
            $this->object = null;
            return;
        }

        $queryBuilder = $event
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('q');

        switch ($this->type) {
            case Type::SESSION_SCHOOL:
                $queryBuilder
                    ->from(SessionSchool::class, 'q');
                break;
            case Type::WORKSHOP:
                $queryBuilder
                    ->from(Workshop::class, 'q');
                break;
            case Type::MOVIE:
                $queryBuilder
                    ->from(Movie::class, 'q');
                break;
            case Type::ART:
                $queryBuilder
                    ->from(Art::class, 'q');
                break;
            case Type::SEMINAR_DEBATE:
                $queryBuilder
                    ->from(Debate::class, 'q');
                break;
            case Type::OPENING:
            case Type::CLOSING:
            case Type::OTHER:
                $queryBuilder
                    ->from(Generic::class, 'q');
                break;
        }

        $queryBuilder
            ->andWhere('q.id = :objectId')
            ->setParameter('objectId', $this->objectId);

        $this->object = $queryBuilder->getQuery()->getOneOrNullResult();
        return $this->object;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    public function hasMeta($metaName)
    {
        foreach ($this->getMeta() as $meta) {
            if($meta->getName() == $metaName) {
                return $meta;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getSubEvent()
    {
        return $this->subEvent;
    }

    /**
     * @param mixed $subEvent
     */
    public function setSubEvent($subEvent)
    {
        $this->subEvent = $subEvent;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
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
     * @return mixed
     */
    public function getAvailablePlaces()
    {
        return $this->availablePlaces;
    }

    /**
     * @param mixed $availablePlaces
     */
    public function setAvailablePlaces($availablePlaces)
    {
        $this->availablePlaces = $availablePlaces;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


}