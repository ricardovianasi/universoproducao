<?php

namespace Application\Entity\Banner;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="banner")
 * @ORM\Entity(repositoryClass="Application\Repository\Banner\Banner")
 */
class Banner extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Site\Site")
	 * @ORM\JoinColumn(name="site_version_id", referencedColumnName="id")
	 */
	private $site;

	/**
	 * @ORM\Column(name="file", type="string")
	 */
	private $file;

	/**
	 * @ORM\Column(name="title", type="string")
	 */
	private $title;

    /**
     * @ORM\Column(name="description", type="string")
     */
    private $description;

	/**
	 * @ORM\Column(name="link", type="string")
	 */
	private $link;

	/**
	 * @ORM\Column(name="status", type="string")
	 */
	private $status;

	/**
	 * @ORM\Column(name="start_date", type="datetime")
	 */
	private $startDate;

	/**
	 * @ORM\Column(name="end_date", type="datetime")
	 */
	private $endDate;

	/**
	 * @ORM\Column(name="`order`", type="integer")
	 */
	private $order;

	/**
	 * @ORM\Column(name="target_blank", type="boolean")
	 */
	private $targetBlank;

	/**
	 * @ORM\Column(name="credits", type="string")
	 */
	private $credits;

	public function __construct()
	{
		$this->startDate = new \DateTime();
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
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @param mixed $file
	 */
	public function setFile($file)
	{
		$this->file = $file;
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
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @param mixed $link
	 */
	public function setLink($link)
	{
		$this->link = $link;
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
	public function getTargetBlank()
	{
		return $this->targetBlank;
	}

	/**
	 * @param mixed $targetBlank
	 */
	public function setTargetBlank($targetBlank)
	{
		$this->targetBlank = (bool) $targetBlank;
	}

	/**
	 * @return mixed
	 */
	public function getCredits()
	{
		return $this->credits;
	}

	/**
	 * @param mixed $credits
	 */
	public function setCredits($credits)
	{
		$this->credits = $credits;
	}
}