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
use Util\Security\Crypt;

/**
 * @ORM\Table(name="user_hash")
 * @ORM\Entity
 */
class Hash extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="logs")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;

    /** @ORM\Column(type="string", nullable=true) */
	private $hash;

	/** @ORM\Column(name="created_at", type="datetime", nullable=true) */
	private $createdAt;

    /** @ORM\Column(name="valid_until", type="datetime", nullable=true) */
    private $validUntil;

	public function __construct($user=null)
    {
        $this->createdAt = new \DateTime();
        $this->validUntil = clone $this->createdAt;
        $this->validUntil->add(new \DateInterval('P7D'));
        $this->hash = Crypt::makePassword(150, true, true, "-_.");

        if($user)
            $this->user = $user;
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
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
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

    /**
     * @return mixed
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * @param mixed $validUntil
     */
    public function setValidUntil($validUntil)
    {
        $this->validUntil = $validUntil;
    }
}