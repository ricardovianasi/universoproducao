<?php

namespace Application\Entity\Tv;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tv")
 * @ORM\Entity(repositoryClass="Application\Repository\Tv\Tv")
 */
class Tv extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
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

	/** @ORM\Column(name="date", type="date") */
	private $date;

	/** @ORM\Column(name="caption", type="string") */
	private $caption;

	/** @ORM\Column(name="youtube", type="string") */
	private $youtube;

    /** @ORM\Column(name="thumb", type="string") */
    private $thumb;

	/** @ORM\Column(name="highlight", type="boolean") */
	private $highlight;

	/** @ORM\Column(name="created_at", type="datetime", nullable=true) */
	private $createdAt;

	/** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
	private $updatedAt;

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
	public function getCaption()
	{
		return $this->caption;
	}

	/**
	 * @param mixed $caption
	 */
	public function setCaption($caption)
	{
		$this->caption = $caption;
	}

	/**
	 * @return mixed
	 */
	public function getYoutube()
	{
		return $this->youtube;
	}

	/**
	 * @param mixed $youtube
	 */
	public function setYoutube($youtube)
	{
		$this->youtube = $youtube;
	}

	/**
	 * @return mixed
	 */
	public function getHighlight()
	{
		return $this->highlight;
	}

	/**
	 * @param mixed $highlight
	 */
	public function setHighlight($highlight)
	{
		$this->highlight = $highlight;
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
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * @param mixed $thumb
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    }
}