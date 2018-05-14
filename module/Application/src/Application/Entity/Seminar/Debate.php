<?php
namespace Application\Entity\Seminar;

use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="seminar_debate")
 * @ORM\Entity(repositoryClass="Application\Repository\Seminar\Debate")
 * @ORM\HasLifecycleCallbacks()
 */
class Debate extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="title", type="string", nullable=true) */
    private $title;

    /** @ORM\Column(name="description", type="text", nullable=true) */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Thematic")
     * @ORM\JoinColumn(name="seminar_thematic_id", referencedColumnName="id")
     */
    private $thematic;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="seminar_category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    private $programing;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\File\File", cascade={"ALL"})
     * @ORM\JoinTable(name="seminar_debate_has_files",
     *      joinColumns={@ORM\JoinColumn(name="seminar_debate_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     */
    private $files;

    public function __construct()
    {
        $this->programing = new ArrayCollection();
        $this->files = new ArrayCollection();
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
    public function getThematic()
    {
        return $this->thematic;
    }

    /**
     * @param mixed $thematic
     */
    public function setThematic($thematic)
    {
        $this->thematic = $thematic;
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
                'type' => Type::SEMINAR_DEBATE,
                'objectId' => $this->id
            ]);
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}