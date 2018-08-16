<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 16/08/2018
 * Time: 15:05
 */

namespace Application\Entity\Proposal;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="workshop_proposal")
 * @ORM\Entity(repositoryClass="Application\Repository\Proposal")
 */
class WorkshopProposal extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    private $name;

    private $objectives;

    private $programContent;

    private $offer;

    private $hours;

    private $hourClass;

    private $daysDuration;

    private $shift;

    private $participantPrerequisites;

    private $didacticResources;

    private $necessaryEquipments;

    private $finalProduct;

    private $valueHourClass;

    private $instructorsCurriculum;

    /** @ORM\Column(name="contact_name", type="string", nullable=true) */
    private $contactName;

    private $email;

    private $phones;

    private $address;

    private $cep;

    private $city;

    private $comments;

    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private $updatedAt;

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

    /**
     * @return mixed
     */
    public function getObjectives()
    {
        return $this->objectives;
    }

    /**
     * @param mixed $objectives
     */
    public function setObjectives($objectives)
    {
        $this->objectives = $objectives;
    }

    /**
     * @return mixed
     */
    public function getProgramContent()
    {
        return $this->programContent;
    }

    /**
     * @param mixed $programContent
     */
    public function setProgramContent($programContent)
    {
        $this->programContent = $programContent;
    }

    /**
     * @return mixed
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * @param mixed $offer
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;
    }

    /**
     * @return mixed
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @param mixed $hours
     */
    public function setHours($hours)
    {
        $this->hours = $hours;
    }

    /**
     * @return mixed
     */
    public function getHourClass()
    {
        return $this->hourClass;
    }

    /**
     * @param mixed $hourClass
     */
    public function setHourClass($hourClass)
    {
        $this->hourClass = $hourClass;
    }

    /**
     * @return mixed
     */
    public function getDaysDuration()
    {
        return $this->daysDuration;
    }

    /**
     * @param mixed $daysDuration
     */
    public function setDaysDuration($daysDuration)
    {
        $this->daysDuration = $daysDuration;
    }

    /**
     * @return mixed
     */
    public function getShift()
    {
        return $this->shift;
    }

    /**
     * @param mixed $shift
     */
    public function setShift($shift)
    {
        $this->shift = $shift;
    }

    /**
     * @return mixed
     */
    public function getParticipantPrerequisites()
    {
        return $this->participantPrerequisites;
    }

    /**
     * @param mixed $participantPrerequisites
     */
    public function setParticipantPrerequisites($participantPrerequisites)
    {
        $this->participantPrerequisites = $participantPrerequisites;
    }

    /**
     * @return mixed
     */
    public function getDidacticResources()
    {
        return $this->didacticResources;
    }

    /**
     * @param mixed $didacticResources
     */
    public function setDidacticResources($didacticResources)
    {
        $this->didacticResources = $didacticResources;
    }

    /**
     * @return mixed
     */
    public function getNecessaryEquipments()
    {
        return $this->necessaryEquipments;
    }

    /**
     * @param mixed $necessaryEquipments
     */
    public function setNecessaryEquipments($necessaryEquipments)
    {
        $this->necessaryEquipments = $necessaryEquipments;
    }

    /**
     * @return mixed
     */
    public function getFinalProduct()
    {
        return $this->finalProduct;
    }

    /**
     * @param mixed $finalProduct
     */
    public function setFinalProduct($finalProduct)
    {
        $this->finalProduct = $finalProduct;
    }

    /**
     * @return mixed
     */
    public function getValueHourClass()
    {
        return $this->valueHourClass;
    }

    /**
     * @param mixed $valueHourClass
     */
    public function setValueHourClass($valueHourClass)
    {
        $this->valueHourClass = $valueHourClass;
    }

    /**
     * @return mixed
     */
    public function getInstructorsCurriculum()
    {
        return $this->instructorsCurriculum;
    }

    /**
     * @param mixed $instructorsCurriculum
     */
    public function setInstructorsCurriculum($instructorsCurriculum)
    {
        $this->instructorsCurriculum = $instructorsCurriculum;
    }

    /**
     * @return mixed
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param mixed $contactName
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
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
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
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

}