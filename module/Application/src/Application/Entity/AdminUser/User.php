<?php

namespace Application\Entity\AdminUser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * User
 *
 * @ORM\Table(name="admin_user")
 * @ORM\Entity(repositoryClass="Application\Repository\AdminUser\User")
 */
class User extends AbstractEntity
	implements InputFilterAwareInterface
{
	protected $inputFilter;

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
	 * @ORM\Column(name="name", type="string", length=150, nullable=false)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=150, nullable=false)
	 */
	private $alias;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=60, nullable=false)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 */
	private $occupation;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 */
	private $phone;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=100, nullable=false)
	 */
	private $password;

	/**
	 * @ORM\Column(name="is_administrator", type="boolean")
	 */
	private $isAdministrator = false;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="status", type="boolean", nullable=false)
	 */
	private $status;

	/**
	 * @ORM\ManyToOne(targetEntity="Profile")
	 * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
	 */
	private $profile;

	/** @ORM\Column(name="confirmed_register", type="boolean") */
	private $confirmedRegister = false;

	/** @ORM\Column(name="change_password_required", type="boolean") */
	private $changePasswordRequired = false;

	/**
	 * @ORM\Column(name="temp_pass", type="string", length=100, nullable=false)
	 */
	private $tempPassword;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime", nullable=true)
	 */
	private $createdAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="updated_at", type="datetime", nullable=true)
	 */
	private $updatedAt;

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

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;
	}

	/**
	 * @return string
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * @param string $alias
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
	}

	/**
	 * @return string
	 */
	public function getOccupation()
	{
		return $this->occupation;
	}

	/**
	 * @param string $occupation
	 */
	public function setOccupation($occupation)
	{
		$this->occupation = $occupation;
	}

	/**
	 * @return string
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @param string $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	public function isAdministrator()
	{
		return $this->isAdministrator;
	}

	public function setIsAdministrator($isAdmin)
	{
		$this->isAdministrator = $isAdmin;
	}

	/**
	 * @return mixed
	 */
	public function getProfile()
	{
		return $this->profile;
	}

	/**
	 * @param mixed $profile
	 */
	public function setProfile($profile)
	{
		$this->profile = $profile;
	}

	/**
	 * @return mixed
	 */
	public function getConfirmedRegister()
	{
		return $this->confirmedRegister;
	}

	/**
	 * @param mixed $confirmedRegister
	 */
	public function setConfirmedRegister($confirmedRegister)
	{
		$this->confirmedRegister = $confirmedRegister;
	}

	/**
	 * @return mixed
	 */
	public function getChangePasswordRequired()
	{
		return $this->changePasswordRequired;
	}

	/**
	 * @param mixed $changePasswordRequired
	 */
	public function setChangePasswordRequired($changePasswordRequired)
	{
		$this->changePasswordRequired = $changePasswordRequired;
	}

	/**
	 * @return mixed
	 */
	public function getTempPassword()
	{
		return $this->tempPassword;
	}

	/**
	 * @param mixed $tempPassword
	 */
	public function setTempPassword($tempPassword)
	{
		$this->tempPassword = $tempPassword;
	}

	// Add content to these methods:
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory = new Factory();

			$inputFilter->add($factory->createInput([
				'name' => 'name',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'alias',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'email',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters(),
				'validators' => [
					['name'=>'email_address']
				]
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'occupation',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'phone',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'profile',
				'required' => false,
			]));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}