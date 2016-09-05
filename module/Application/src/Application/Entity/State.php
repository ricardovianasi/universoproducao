<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * State
 *
 * @ORM\Table(name="state")
 * @ORM\Entity
 */
class State extends AbstractEntity
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
	 * @ORM\Column(name="acronyme", type="string", length=45, nullable=true)
	 */
	private $acronyme;

	/**
	 * @var unknown
	 *
	 *  @ORM\OneToMany(targetEntity="City", mappedBy="state")
	 */
	private $cities;

	private function __construct() {
		$this->cities = new ArrayCollection();
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

	public function getAcronyme()
	{
		return $this->acronyme;
	}

	public function setAcronyme($acronyme)
	{
		$this->acronyme = $acronyme;
	}

	public function getCities()
	{
		return $this->cities;
	}

	public function setCities($cities)
	{
		$this->cities = $cities;
	}
}