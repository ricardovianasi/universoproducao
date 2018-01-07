<?php
namespace Application\Entity\Programing;

use Application\Entity\Art\Art;
use Application\Entity\Movie\Movie;
use Application\Entity\Seminar\Debate;
use Application\Entity\Workshop\Workshop;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="programing_generic")
 * @ORM\Entity(repositoryClass="Application\Repository\Programing\Generic")
 * @ORM\HasLifecycleCallbacks()
 */
class Generic extends AbstractEntity
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

    /** @ORM\Column(name="`title`", type="text") */
	private $title;

    /** @ORM\Column(name="`description`", type="text") */
	private $description;

    /** @ORM\Column(name="`type`", type="string") */
	private $type;

	private $programing;

	public function __construct()
    {

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
     * @return ArrayCollection
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
                'type' => [Type::OPENING, Type::OTHER, Type::CLOSING],
                'objectId' => $this->id
            ]);
    }
}