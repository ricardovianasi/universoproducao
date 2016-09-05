<?php

namespace Application\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * Action
 *
 * @ORM\Table(name="action", indexes={@ORM\Index(name="fk_action_controller1_idx", columns={"id_controller"})})
 * @ORM\Entity
 */
class Action extends AbstractEntity
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
	 * @ORM\ManyToOne(targetEntity="Controller", inversedBy="actions")
	 * @ORM\JoinColumn(name="id_controller", referencedColumnName="id")
	 */
	private $controller;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alias", type="string", length=60, nullable=false)
	 */
	private $label;

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getController()
	{
		return $this->controller;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setController($controller)
	{
		$this->controller = $controller;
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
}
