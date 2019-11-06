<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 01/02/2016
 * Time: 11:31
 */
namespace Application\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="user_category")
 * @ORM\Entity(repositoryClass="Application\Repository\User\Category")
 */
class Category extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/** @ORM\Column(type="string") */
	private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
	private $parent;

    /** @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"ALL"}) */
    private $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
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
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }
}