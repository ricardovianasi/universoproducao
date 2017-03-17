<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 15/03/2017
 * Time: 14:26
 */

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
 * @ORM\Table(name="user_company")
 * @ORM\Entity(repositoryClass="Application\Repository\User\Company")
 */
class Company extends AbstractEntity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="cnpj", type="string") */
    private $cnpj;

    /** @ORM\Column(name="name", type="string") */
    private $name;

    /** @ORM\Column(name="social_reason", type="string") */
    private $socialReason;

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


    /** @ORM\Column(name="email", type="string") */
    private $email;

    /** @ORM\Column(name="site", type="string") */
    private $site;

    private $ownerUser;

    /** @ORM\Column(name="relationship", type="string") */
    private $relationship;

    /**
     * Many User have Many Phonenumbers.
     * @ORM\ManyToMany(targetEntity="Application\Entity\Phone\Phone")
     * @ORM\JoinTable(name="user_company_phones",
     *      joinColumns={@ORM\JoinColumn(name="user_company_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="phone_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $phones;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
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
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param mixed $cnpj
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
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
    public function getSocialReason()
    {
        return $this->socialReason;
    }

    /**
     * @param mixed $socialReason
     */
    public function setSocialReason($socialReason)
    {
        $this->socialReason = $socialReason;
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
    public function getOwnerUser()
    {
        return $this->ownerUser;
    }

    /**
     * @param mixed $ownerUser
     */
    public function setOwnerUser($ownerUser)
    {
        $this->ownerUser = $ownerUser;
    }

    /**
     * @return mixed
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * @param mixed $relationship
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
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
}