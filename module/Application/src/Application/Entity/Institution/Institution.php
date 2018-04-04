<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/02/2018
 * Time: 15:21
 */

namespace Application\Entity\Institution;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="instituition")
 * @ORM\Entity
 */
class Institution extends AbstractEntity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="social_name", type="string", nullable=true) */
    private $socialName;

    /** @ORM\Column(name="fantasy_name", type="string", nullable=true) */
    private $fantasyName;

    /** @ORM\Column(name="cnpj", type="string", nullable=true) */
    private $cnpj;

    /** @ORM\Column(type="string", nullable=true) */
    private $address;

    /** @ORM\Column(name="legal_representative", type="string", nullable=true) */
    private $legalRepresentative;

    /** @ORM\Column(type="string", nullable=true) */
    private $phone;

    /** @ORM\Column(name="mobile_phone", type="string", nullable=true) */
    private $mobilePhone;

    /** @ORM\Column(name="site", type="string", nullable=true) */
    private $site;

    /** @ORM\Column(type="string", nullable=true) */
    private $email;

    /** @ORM\Column(type="text", nullable=true) */
    private $description;

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
    public function getSocialName()
    {
        return $this->socialName;
    }

    /**
     * @param mixed $socialName
     */
    public function setSocialName($socialName)
    {
        $this->socialName = $socialName;
    }

    /**
     * @return mixed
     */
    public function getFantasyName()
    {
        return $this->fantasyName;
    }

    /**
     * @param mixed $fantasyName
     */
    public function setFantasyName($fantasyName)
    {
        $this->fantasyName = $fantasyName;
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
    public function getLegalRepresentative()
    {
        return $this->legalRepresentative;
    }

    /**
     * @param mixed $legalRepresentative
     */
    public function setLegalRepresentative($legalRepresentative)
    {
        $this->legalRepresentative = $legalRepresentative;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * @param mixed $mobilePhone
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}