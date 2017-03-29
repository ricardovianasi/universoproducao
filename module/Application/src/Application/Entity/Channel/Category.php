<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 10/03/2017
 * Time: 14:14
 */

namespace Application\Entity\Channel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="channel_category")
 * @ORM\Entity(repositoryClass="Application\Repository\Channel\Category")
 */
class Category extends AbstractEntity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="name", type="string", nullable=true) */
    private $name;

    /** @ORM\Column(name="slug", type="string", nullable=true) */
    private $slug;

    /** @ORM\Column(name="is_visible", type="boolean", nullable=true) */
    private $isVisible = true;

    /**
     * @ORM\ManyToMany(targetEntity="Video", mappedBy="categories", fetch="EXTRA_LAZY")
     */
    private $videos;

    public function __construct()
    {
        $this->videos = new ArrayCollection();
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * @param mixed $isVisible
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;
    }
}