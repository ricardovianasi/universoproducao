<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 01/02/2016
 * Time: 11:31
 */

namespace Application\Entity\ExternalUser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="external_user_log")
 * @ORM\Entity
 */
class Log extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/** @ORM\Column(type="string") */
	private $log;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="logs")
	 * @ORM\JoinColumn(name="external_user_id", referencedColumnName="id")
	 */
	private $user;

	/** @ORM\Column(name="created_at", type="datetime", nullable=true) */
	private $createdAt;

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
	public function getLog()
	{
		return $this->log;
	}

	/**
	 * @param mixed $log
	 */
	public function setLog($log)
	{
		$this->log = $log;
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
}