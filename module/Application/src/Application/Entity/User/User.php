<?php
namespace Application\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;
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
class User extends AbstractEntity implements InputFilterAwareInterface
{
	protected $inputFilter;

	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/** @ORM\Column(name="first_name", type="string") */
	private $firstName;

	/** @ORM\Column(name="last_name", type="string") */
	private $lastName;

	/** @ORM\Column(name="cpf", type="string") */
	private $cpf;

	/** @ORM\Column(name="email", type="string") */
	private $email;

	/** @ORM\Column(name="site", type="string") */
	private $site;

	/** @ORM\Column(name="password", type="string") */
	private $password;

	/** @ORM\Column(name="temp_pass", type="string", length=100, nullable=false) */
	private $tempPassword;

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

	/** @ORM\Column(name="old_pass", type="string") */
	private $oldPass;

	/** @ORM\Column(name="old_login", type="string") */
	private $oldLogin;

	/** @ORM\Column(name="facebook", type="string") */
	private $facebook;

	/** @ORM\Column(name="twitter", type="string") */
	private $twitter;

	/** @ORM\Column(name="confirmed_register", type="boolean") */
	private $confirmedRegister = false;

	/** @ORM\Column(name="change_password_required", type="boolean") */
	private $changePasswordRequired = false;

    /**
     * @ORM\OneToOne(targetEntity="Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
	private $company;

    /**
     * Many User have Many Phonenumbers.
     * @ORM\ManyToMany(targetEntity="Application\Entity\Phone\Phone", fetch="EAGER", cascade="ALL")
     * @ORM\JoinTable(name="user_phones",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="phone_id", referencedColumnName="id")}
     *      )
     */
	private $phones;

    /**
     * @ORM\OneToMany(targetEntity="Dependent", mappedBy="user", cascade="ALL")
     */
	private $dependents;

	/**
	 * @ORM\OneToMany(targetEntity="Log", mappedBy="user")
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
	public function getCpf()
	{
		return $this->cpf;
	}

	/**
	 * @param mixed $cpf
	 */
	public function setCpf($cpf)
	{
		$this->cpf = $cpf;
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
	public function getOldPass()
	{
		return $this->oldPass;
	}

	/**
	 * @param mixed $oldPass
	 */
	public function setOldPass($oldPass)
	{
		$this->oldPass = $oldPass;
	}

	/**
	 * @return mixed
	 */
	public function getOldLogin()
	{
		return $this->oldLogin;
	}

	/**
	 * @param mixed $oldLogin
	 */
	public function setOldLogin($oldLogin)
	{
		$this->oldLogin = $oldLogin;
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
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * @param mixed $lastName
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @param mixed $firstName
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
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

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
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
     * @return mixed
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

	public function getInputFilter()
	{
		if(!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory = new Factory();

			$inputFilter->add($factory->createInput([
				'name' => 'cpf',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'first_name',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'last_name',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'email',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters(),
				'validators' => [
					['name' => 'email_address']
				]
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'birth_date',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'cep',
				'required' => true,
				'filters'  => array_merge(
					$this->getDefaultInputFilters(),
					[
						['name' => 'Digits']
					]
				)
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'address',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'number',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

			$inputFilter->add($factory->createInput([
				'name' => 'district',
				'required' => true,
				'filters'  => $this->getDefaultInputFilters()
			]));

            $inputFilter->add($factory->createInput([
                'name' => 'gender',
                'required' => true,
                'filters'  => $this->getDefaultInputFilters()
            ]));


            $inputFilter->add($factory->createInput([
				'name' => 'confirmed_register',
				'required' => false
			]));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}