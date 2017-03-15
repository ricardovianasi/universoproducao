<?php
namespace Application\Entity\Programation;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="highlight_programation")
 * @ORM\Entity(repositoryClass="Application\Repository\Programation\Highlight")
 */
class Highlight extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\AdminUser\User")
	 * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
	 */
	private $author;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Site\Site")
	 * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
	 */
	private $site;

	/** @ORM\Column(type="date") */
	private $date;

	/** @ORM\Column(type="string") */
	private $hour;

	/** @ORM\Column(type="string") */
	private $title;

	/** @ORM\Column(type="string") */
	private $subtitle;

	/** @ORM\Column(type="string") */
	private $place;

	/** @ORM\Column(type="string") */
	private $direction;

	/** @ORM\Column(type="string") */
	private $position;

	/** @ORM\Column(type="string") */
	private $photo;

	/** @ORM\Column(type="string") */
	private $url;

	/** @ORM\Column(name="is_highlight", type="boolean") */
	private $isHighlight;

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
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * @param mixed $site
	 */
	public function setSite($site)
	{
		$this->site = $site;
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
		$this->date = $this->parseData($date);
	}

	/**
	 * @return mixed
	 */
	public function getHour()
	{
		return $this->hour;
	}

	/**
	 * @param mixed $hour
	 */
	public function setHour($hour)
	{
		$this->hour = $hour;
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
	public function getSubtitle()
	{
		return $this->subtitle;
	}

	/**
	 * @param mixed $subtitle
	 */
	public function setSubtitle($subtitle)
	{
		$this->subtitle = $subtitle;
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
	public function getDirection()
	{
		return $this->direction;
	}

	/**
	 * @param mixed $direction
	 */
	public function setDirection($direction)
	{
		$this->direction = $direction;
	}

	/**
	 * @return mixed
	 */
	public function getPhoto()
	{
		return $this->photo;
	}

	/**
	 * @param mixed $photo
	 */
	public function setPhoto($photo)
	{
		$this->photo = $photo;
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param mixed $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return mixed
	 */
	public function getIsHighlight()
	{
		return $this->isHighlight;
	}

	/**
	 * @param mixed $isHighlight
	 */
	public function setIsHighlight($isHighlight)
	{
		$this->isHighlight = $isHighlight;
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
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param mixed $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}
}