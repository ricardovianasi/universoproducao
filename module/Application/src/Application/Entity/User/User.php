<?php
namespace Application\Entity\User;

use Application\Entity\City;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;
use Util\Validator\Identifier;
use Zend\InputFilter\Factory;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Application\Repository\User\User")
 */
class User extends AbstractEntity
{
    const TYPE_PESSOA_FISICA = 'pf';
    const TYPE_PESSOA_JURIDICA = 'pj';
    const TYPE_CADASTRO_INTERNACIONAL = 'pi';

	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

    /** @ORM\Column(name="name", type="string") */
	private $name;

	/** @ORM\Column(name="alias", type="string") */
	private $alias;

	/** @ORM\Column(name="identifier", type="string") */
	private $identifier;

	/** @ORM\Column(name="email", type="string") */
	private $email;

	/** @ORM\Column(name="site", type="string") */
	private $site;

	/** @ORM\Column(name="password", type="string") */
	private $password;

	/** @ORM\Column(name="birth_date", type="datetime") */
	private $birthDate;

	/** @ORM\Column(name="address", type="string") */
	private $address;

	/** @ORM\Column(name="number", type="string") */
	private $number;

	/** @ORM\Column(name="complement", type="string") */
	private $complement;

	/** @ORM\Column(name="district", type="string") */
	private $district;

	/** @ORM\Column(name="cep", type="string") */
	private $cep;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\City")
	 * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
	 */
	private $city;

    /** @ORM\Column(name="gender", type="string") */
	private $gender;

	/** @ORM\Column(name="facebook", type="string") */
	private $facebook;

	/** @ORM\Column(name="twitter", type="string") */
	private $twitter;

	/** @ORM\Column(name="confirmed_register", type="boolean") */
	private $confirmedRegister = false;

	/** @ORM\Column(name="change_password_required", type="boolean") */
	private $changePasswordRequired = false;

    /** @ORM\Column(name="update_register_required", type="boolean") */
	private $updateRegisterRequired = true;

    /** @ORM\OneToMany(targetEntity="Application\Entity\Phone\Phone", mappedBy="user", cascade="ALL") */
	private $phones;

    /** @ORM\OneToMany(targetEntity="User", mappedBy="parent") */
	private $dependents;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="dependents")
     * @ORM\JoinColumn(name="parent_user_id", referencedColumnName="id")
     */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Log", mappedBy="user")
     * @ORM\OrderBy({"createdAt" = "DESC"})
	 */
	private $logs;

	/** @ORM\Column(name="created_at", type="datetime", nullable=true) */
	private $createdAt;

	/** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
	private $updatedAt;

	public function __construct()
	{
		$this->logs = new ArrayCollection();
		$this->phones = new ArrayCollection();
		$this->dependents = new ArrayCollection();
	}

    /**
     * @return mixed
     */
    public function getType()
    {
        if(!$this->getIdentifier()) {
            return false;
        }

        $identifierValidator = new Identifier();
        if($identifierValidator->validateCPF($this->getIdentifier())) {
            return self::TYPE_PESSOA_FISICA;
        } elseif($identifierValidator->validateCNPJ($this->getIdentifier())) {
            return self::TYPE_PESSOA_JURIDICA;
        } elseif($identifierValidator->validatePassport($this->getIdentifier())) {
            return self::TYPE_CADASTRO_INTERNACIONAL;
        }

        return false;
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
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * @param mixed $site
	 */
	public function setSite($site)
	{
		$this->site = $site;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
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
		$this->birthDate = $this->parseData($birthDate);
	}

	/**
	 * @return mixed
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @param mixed $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
	}

	/**
	 * @return mixed
	 */
	public function getDistrict()
	{
		return $this->district;
	}

	/**
	 * @param mixed $district
	 */
	public function setDistrict($district)
	{
		$this->district = $district;
	}

	/**
	 * @return mixed
	 */
	public function getCep()
	{
		return $this->cep;
	}

	/**
	 * @param mixed $cep
	 */
	public function setCep($cep)
	{
		$this->cep = $cep;
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
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
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * @param mixed $updatedAt
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;
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
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

	/**
	 * @return mixed
	 */
	public function getFacebook()
	{
		return $this->facebook;
	}

	/**
	 * @param mixed $facebook
	 */
	public function setFacebook($facebook)
	{
		$this->facebook = $facebook;
	}

	/**
	 * @return mixed
	 */
	public function getTwitter()
	{
		return $this->twitter;
	}

	/**
	 * @param mixed $twitter
	 */
	public function setTwitter($twitter)
	{
		$this->twitter = $twitter;
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
	public function getLogs()
	{
		return $this->logs;
	}

	/**
	 * @param mixed $logs
	 */
	public function setLogs($logs)
	{
		$this->logs = $logs;
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
	public function getComplement()
	{
		return $this->complement;
	}

	/**
	 * @param mixed $complement
	 */
	public function setComplement($complement)
	{
		$this->complement = $complement;
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
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @param mixed $phones
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;
    }

    /**
     * @return ArrayCollection
     */
    public function getDependents()
    {
        return $this->dependents;
    }

    /**
     * @param mixed $dependents
     */
    public function setDependents($dependents)
    {
        $this->dependents = $dependents;
    }

    public function hasDependents()
    {
        return (boolean) $this->getDependents()->count();
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
    public function getUpdateRegisterRequired()
    {
        return $this->updateRegisterRequired;
    }

    /**
     * @param mixed $updateRegisterRequired
     */
    public function setUpdateRegisterRequired($updateRegisterRequired)
    {
        $this->updateRegisterRequired = $updateRegisterRequired;
    }

    public function getFullAddress()
    {
        $address = [
            $this->getAddress(),
            $this->getNumber(),
            $this->getCep()
        ];
        if($this->getCity() && $this->getCity() instanceof City) {
            $address[] = $this->getCity()->getState()->getName();
            $address[] = $this->getCity()->getName();
        }

        return implode(',', $address);
    }

    public function _toArray()
    {
        $phonesArray = [];
        foreach ($this->getPhones() as $p) {
            $phonesArray[] = $p->_toArray();
        }

        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'identifier' => $this->getIdentifier(),
            'email' => $this->getEmail(),
            'type' => $this->getType(),
            'phones' => $phonesArray
        ];

        return $data;
    }

    /**
     * @return User
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
}