<?php
namespace Application\Entity\Event;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;
use Application\Annotations as APP;

/**
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="Application\Repository\Event\Event")
 * @APP\OrderBy({"startDate"="DESC"})
 */
class Event extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/** @ORM\Column(name="type", type="string") */
	private $type;

	/** @ORM\Column(name="full_name", type="string") */
	private $fullName;

	/** @ORM\Column(name="short_name", type="string") */
	private $shortName;

	/** @ORM\Column(type="integer") */
	private $edition;

	/** @ORM\Column(name="start_date", type="date") */
	private $startDate;

	/** @ORM\Column(name="end_date", type="date") */
	private $endDate;

	/** @ORM\Column(name="description", type="text") */
	private $description;

	
	private $site;

	/** @ORM\Column(type="string") */
	private $logo;

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
	public function getFullName()
	{
		return $this->fullName;
	}

	/**
	 * @param mixed $fullName
	 */
	public function setFullName($fullName)
	{
		$this->fullName = $fullName;
	}

	/**
	 * @return mixed
	 */
	public function getShortName()
	{
		return $this->shortName;
	}

	/**
	 * @param mixed $shortName
	 */
	public function setShortName($shortName)
	{
		$this->shortName = $shortName;
	}

	/**
	 * @return mixed
	 */
	public function getEdition()
	{
		return $this->edition;
	}

	/**
	 * @param mixed $edition
	 */
	public function setEdition($edition)
	{
		$this->edition = $edition;
	}

	/**
	 * @return mixed
	 */
	public function getStartDate()
	{
		return $this->startDate;
	}

	/**
	 * @param mixed $startDate
	 */
	public function setStartDate($startDate)
	{
		$this->startDate = $this->parseData($startDate);
	}

	/**
	 * @return mixed
	 */
	public function getEndDate()
	{
		return $this->endDate;
	}

	/**
	 * @param mixed $endDate
	 */
	public function setEndDate($endDate)
	{
		$this->endDate = $this->parseData($endDate);
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
	public function getLogo()
	{
		return $this->logo;
	}

	/**
	 * @param mixed $logo
	 */
	public function setLogo($logo)
	{
		$this->logo = $logo;
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
}