<?php

namespace Application\Entity\AdminUser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * Profile
 *
 * @ORM\Table(name="profile")
 * @ORM\Entity(repositoryClass="Application\Repository\AdminUser\Profile")
 */
class Profile extends AbstractEntity
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
	 * @var boolean
	 *
	 * @ORM\Column(name="status", type="boolean", nullable=false)
	 */
	private $status = '1';

	/**
	 * @ORM\ManyToMany(targetEntity="Action")
	 * @ORM\JoinTable(name="profile_actions",
	 *      joinColumns={@ORM\JoinColumn(name="id_profile", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="id_action", referencedColumnName="id")}
	 * )
	 */
	private $actions;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->actions = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getActions()
	{
		return $this->actions;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function setActions($actions)
	{
		$this->actions = $actions;
	}
}