<?php
namespace Application\Entity\Site;

use Doctrine\Common\Collections\ArrayCollection;
use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="site")
 * @ORM\Entity(repositoryClass="Application\Repository\Site\Site")
 */
class Site extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\User\User")
	 * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
	 */
	private $author;

	/**
	 * @ORM\OneToOne(targetEntity="Application\Entity\Event\Event")
	 * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
	 */
	private $event;

	/**
	 * @ORM\Column(name="name", type="string", nullable=false)
	 */
	private $name;

	/**
	 * @ORM\Column(name="title", type="string")
	 */
	private $title;

	/**
	 * @ORM\Column(name="status", type="boolean")
	 */
	private $status;

	/** @ORM\OneToMany(targetEntity="SiteMeta", mappedBy="site", cascade={"ALL"}) */
	public $meta;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Site\Language")
     * @ORM\JoinTable(name="site_has_languages",
     *   joinColumns={@ORM\JoinColumn(name="site_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="language_id", referencedColumnName="id")}
     * )
     */
	public $languages;

	/**
	 * @ORM\Column(name="updated_at", type="datetime", nullable=true)
	 */
	private $createdAt;

	/**
	 * @ORM\Column(name="created_at", type="datetime", nullable=true)
	 */
	private $updatedAt;

	public function __construct()
	{
		$this->meta = new ArrayCollection();
		$this->languages = new ArrayCollection();
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
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * @param mixed $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
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

	public function addMeta(SiteMeta $meta)
	{
		$meta->setSite($this);

		if(!$this->meta->contains($meta)) {
			$this->meta->add($meta);
		}

		return $this;
	}

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param mixed $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

	public function hasLanguages()
    {
        return $this->languages->count();
    }
}