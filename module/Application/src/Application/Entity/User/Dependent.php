<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 07/01/2016
 * Time: 14:20
 */

namespace Application\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="user_dependents")
 * @ORM\Entity
 */
class Dependent extends AbstractEntity
{
	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="dependents")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;

    /** @ORM\Column(name="name", type="string") */
    private $name;

    /** @ORM\Column(name="email", type="string") */
    private $email;

    /** @ORM\Column(name="identifier", type="string") */
    private $identifier;

    /** @ORM\Column(name="birth_date", type="date") */
    private $birthDate;

    /** @ORM\Column(name="gender", type="string") */
    private $gender;

    public function __construct($data = [])
    {
        $this->setData($data);
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->parseData($birthDate, $this->birthDate, false);
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }
}