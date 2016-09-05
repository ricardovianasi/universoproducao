<?php
namespace Application\Entity\Movie;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="movie_color")
 * @ORM\Entity
 */
class Color extends AbstractEntity
{
	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/** @ORM\Column(name="name", type="string", nullable=true) */
	private $name;

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

}