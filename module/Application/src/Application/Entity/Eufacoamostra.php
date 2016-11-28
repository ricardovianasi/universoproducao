<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * eufacoamostra
 *
 * @ORM\Table(name="eufacoamostra")
 * @ORM\Entity(repositoryClass="Application\Repository\Eufacoamostra")
 */
class Eufacoamostra extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false) @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/** @ORM\Column(name="title", type="string", length=60, nullable=false) */
	private $title;

    /** @ORM\Column(name="description", type="string", length=60, nullable=false) */
    private $description;

    /** @ORM\Column(name="thumb", type="string", length=60, nullable=false) */
    private $thumb;

    /** @ORM\Column(name="video", type="string", length=60, nullable=false) */
    private $video;

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

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param mixed $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }
}
