<?php

namespace Application\Entity\AdminUser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * Controller
 *
 * @ORM\Table(name="controller")
 * @ORM\Entity
 */
class Controller extends AbstractEntity
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=60, nullable=false)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alias", type="string", length=60, nullable=false)
	 */
	private $label;

	/**
	 * @ORM\OneToMany(targetEntity="Action", mappedBy="controller")
	 */
	private $actions;

	public function __construct()
	{
		$this->actions = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @return mixed
	 */
	public function getActions()
	{
		return $this->actions;
	}

	/**
	 * @param mixed $actions
	 */
	public function setActions($actions)
	{
		$this->actions = $actions;
	}
}
