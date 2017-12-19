<?php
namespace Application\Entity\Programing;

use Application\Entity\Workshop\Workshop;
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
        if(is_string($startTime)) {
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
        if(is_string($endTime)) {
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
            case Type::WORKSHOP:
                $queryBuilder
                    ->from(Workshop::class, 'q');
                break;
        }

        $queryBuilder
            ->andWhere('q.id = :objectId')
            ->setParameter('objectId', $this->objectId);

        $this->object = $queryBuilder->getQuery()->getOneOrNullResult();
        return $this->object;
    }
}