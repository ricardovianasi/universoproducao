<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/03/2018
 * Time: 09:27
 */

namespace Application\Entity\EducationalProject;

use Application\Entity\Registration\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="educational_project")
 * @ORM\Entity(repositoryClass="Application\Repository\EducationalProject\EducationalProject")
 */
class EducationalProject extends AbstractEntity
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Registration\Registration")
     * @ORM\JoinColumn(name="registration_id", referencedColumnName="id")
     */
    private $registration;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\User\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="educational_project_categories_id", referencedColumnName="id")
     */
    private $category;

    /** @ORM\Column(name="status", type="string", nullable=true) */
    private $status = Status::ON_EVALUATION;

    /** @ORM\Column(name="proposers_name", type="text") */
    private $proposersName;

    /** @ORM\Column(name="responsible", type="string") */
    private $responsible;

    /** @ORM\Column(name="institution", type="string") */
    private $institution;

    /** @ORM\Column(name="institution_address", type="string") */
    private $institutionAddress;

    /** @ORM\Column(name="institution_uf", type="string") */
    private $institutionUf;

    /** @ORM\Column(name="institution_phone", type="string") */
    private $institutionPhone;

    /** @ORM\Column(name="institution_mobile_phone", type="string") */
    private $institutionMobilePhone;

    /** @ORM\Column(name="institution_email", type="string") */
    private $institutionEmail;

    /** @ORM\Column(name="title", type="string") */
    private $title;

    /** @ORM\Column(name="presentation", type="text") */
    private $presentation;

    /** @ORM\Column(name="curriculum", type="text") */
    private $curriculum;

    /** @ORM\Column(name="complete_text", type="text") */
    private $completeText;

    /** @ORM\Column(name="references", type="text") */
    private $references;

    /** @ORM\Column(name="links", type="text") */
    private $links;

    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\File\File", cascade={"ALL"})
     * @ORM\JoinTable(name="educational_project_files",
     *      joinColumns={@ORM\JoinColumn(name="educational_project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     */
    private $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
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
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param mixed $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
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
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getProposersName()
    {
        return $this->proposersName;
    }

    /**
     * @param mixed $proposersName
     */
    public function setProposersName($proposersName)
    {
        $this->proposersName = $proposersName;
    }

    /**
     * @return mixed
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param mixed $institution
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    /**
     * @return mixed
     */
    public function getInstitutionAddress()
    {
        return $this->institutionAddress;
    }

    /**
     * @param mixed $institutionAddress
     */
    public function setInstitutionAddress($institutionAddress)
    {
        $this->institutionAddress = $institutionAddress;
    }

    /**
     * @return mixed
     */
    public function getInstitutionUf()
    {
        return $this->institutionUf;
    }

    /**
     * @param mixed $institutionUf
     */
    public function setInstitutionUf($institutionUf)
    {
        $this->institutionUf = $institutionUf;
    }

    /**
     * @return mixed
     */
    public function getInstitutionPhone()
    {
        return $this->institutionPhone;
    }

    /**
     * @param mixed $institutionPhone
     */
    public function setInstitutionPhone($institutionPhone)
    {
        $this->institutionPhone = $institutionPhone;
    }

    /**
     * @return mixed
     */
    public function getInstitutionMobilePhone()
    {
        return $this->institutionMobilePhone;
    }

    /**
     * @param mixed $institutionMobilePhone
     */
    public function setInstitutionMobilePhone($institutionMobilePhone)
    {
        $this->institutionMobilePhone = $institutionMobilePhone;
    }

    /**
     * @return mixed
     */
    public function getInstitutionEmail()
    {
        return $this->institutionEmail;
    }

    /**
     * @param mixed $institutionEmail
     */
    public function setInstitutionEmail($institutionEmail)
    {
        $this->institutionEmail = $institutionEmail;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param mixed $presentation
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }

    /**
     * @return mixed
     */
    public function getCurriculum()
    {
        return $this->curriculum;
    }

    /**
     * @param mixed $curriculum
     */
    public function setCurriculum($curriculum)
    {
        $this->curriculum = $curriculum;
    }

    /**
     * @return mixed
     */
    public function getCompleteText()
    {
        return $this->completeText;
    }

    /**
     * @param mixed $completeText
     */
    public function setCompleteText($completeText)
    {
        $this->completeText = $completeText;
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
    public function getResponsible()
    {
        return $this->responsible;
    }

    /**
     * @param mixed $responsible
     */
    public function setResponsible($responsible)
    {
        $this->responsible = $responsible;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @param mixed $references
     */
    public function setReferences($references)
    {
        $this->references = $references;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param mixed $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }
}