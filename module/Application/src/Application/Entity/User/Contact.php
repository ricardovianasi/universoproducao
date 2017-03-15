<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 07/01/2016
 * Time: 14:20
 */

namespace Application\Entity\User;


use Util\Entity\AbstractEntity;

class Contact extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="contacts")
	 * @ORM\JoinColumn(name="external_user_id", referencedColumnName="id")
	 */
	private $user;

	/** @ORM\Column(name="number", type="string") */
	private $number;

	/** @ORM\Column(name="is_main", type="boolean") */
	private $isMain;

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
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}

	/**
	 * @return mixed
	 */
	public function getNumber()
	{
		return $this->number;
	}

	/**
	 * @param mixed $number
	 */
	public function setNumber($number)
	{
		$this->number = $number;
	}

	/**
	 * @return mixed
	 */
	public function getIsMain()
	{
		return $this->isMain;
	}

	/**
	 * @param mixed $isMain
	 */
	public function setIsMain($isMain)
	{
		$this->isMain = $isMain;
	}
}