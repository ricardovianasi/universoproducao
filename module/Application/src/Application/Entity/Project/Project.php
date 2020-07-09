<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/02/2018
 * Time: 15:21
 */

namespace Application\Entity\Project;

use Application\Entity\Registration\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Application\Repository\Project\Project")
 */
class Project extends AbstractEntity
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

    /** @ORM\Column(name="status", type="string", nullable=true) */
    private $status = Status::ON_EVALUATION;

    /** @ORM\Column(name="title", type="string", nullable=true) */
    private $title;

    /** @ORM\Column(name="english_title", type="string", nullable=true) */
    private $englishTitle;

    /** @ORM\Column(name="protocol_registration_law", type="string", nullable=true) */
    private $protocolRegistrationLaw;

    /** @ORM\Column(name="presentation", type="string", nullable=true) */
    private $presentation;

    /** @ORM\Column(name="short_sinopse", type="string", nullable=true) */
    private $shortSinopse;

    /** @ORM\Column(name="short_sinopse_english", type="text", nullable=true) */
    private $shortSinopseEnglish;

    /** @ORM\Column(name="long_sinopse", type="string", nullable=true) */
    private $long_sinopse;

    /** @ORM\Column(name="argument", type="string", nullable=true) */
    private $argument;

    /** @ORM\Column(name="director_notes", type="string", nullable=true) */
    private $directorNotes;

    /** @ORM\Column(name="producer_notes", type="string", nullable=true) */
    private $producerNotes;

    /** @ORM\Column(name="movie_length", type="time", nullable=true) */
    private $movieLength;

    /** @ORM\Column(name="estimated_value", type="string", nullable=true) */
    private $estimatedValue;

    /** @ORM\Column(name="locations", type="string", nullable=true) */
    private $locations;

    /** @ORM\Column(name="value_captured_resources", type="string", nullable=true) */
    private $valueCapturedResources;

    /** @ORM\Column(name="value_captured_services", type="string", nullable=true) */
    private $valueCapturedServices;

    /** @ORM\Column(name="estimated_time_filming", type="string", nullable=true) */
    private $estimatedTimeFilming;

    /** @ORM\Column(name="related_partners", type="string", nullable=true) */
    private $relatedPartners;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\State")
     * @ORM\JoinColumn(name="state_production_id", referencedColumnName="id")
     */
    private $stateProduction;

    /** @ORM\OneToMany(targetEntity="People", mappedBy="project", cascade={"ALL"}) */
    private $peoples;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Institution\Institution", cascade={"ALL"})
     * @ORM\JoinColumn(name="instituition_id", referencedColumnName="id")
     */
    private $instituition;

    /**
     * @ORM\ManyToMany(targetEntity="Options")
     * @ORM\JoinTable(name="project_has_options",
     *   joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="project_options_id", referencedColumnName="id")}
     * )
     */
    private $options;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\File\File", cascade={"ALL"})
     * @ORM\JoinTable(name="project_has_files",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     */
    private $files;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\File\File", cascade={"ALL"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\File\File", cascade={"ALL"})
     * @ORM\JoinColumn(name="script_id", referencedColumnName="id")
     */
    private $script;

    /** @ORM\Column(name="links", type="text", nullable=true) */
    private $links;

    /** @ORM\Column(name="movie_link", type="text", nullable=true) */
    private $movieLink;

    /** @ORM\Column(name="movie_pass", type="text", nullable=true) */
    private $moviePass;

    /** @ORM\Column(name="participated_other_festivals", type="text", nullable=true) */
    private $participatedOtherFestivals;

    /** @ORM\Column(name="year_of_participation", type="integer", nullable=true) */
    private $yearOfParticipation;

    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private $updatedAt;

    public function __construct()
    {
        $this->peoples = new ArrayCollection();
        $this->options = new ArrayCollection();
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
    public function getEnglishTitle()
    {
        return $this->englishTitle;
    }

    /**
     * @param mixed $englishTitle
     */
    public function setEnglishTitle($englishTitle)
    {
        $this->englishTitle = $englishTitle;
    }

    /**
     * @return mixed
     */
    public function getProtocolRegistrationLaw()
    {
        return $this->protocolRegistrationLaw;
    }

    /**
     * @param mixed $protocolRegistrationLaw
     */
    public function setProtocolRegistrationLaw($protocolRegistrationLaw)
    {
        $this->protocolRegistrationLaw = $protocolRegistrationLaw;
    }

    public function getHasProtocolRegistrationLaw()
    {
        if($this->getProtocolRegistrationLaw() === null) {
            return null;
        }

        return !empty($this->getProtocolRegistrationLaw());
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
    public function getShortSinopse()
    {
        return $this->shortSinopse;
    }

    /**
     * @param mixed $shortSinopse
     */
    public function setShortSinopse($shortSinopse)
    {
        $this->shortSinopse = $shortSinopse;
    }

    /**
     * @return mixed
     */
    public function getLongSinopse()
    {
        return $this->long_sinopse;
    }

    /**
     * @param mixed $long_sinopse
     */
    public function setLongSinopse($long_sinopse)
    {
        $this->long_sinopse = $long_sinopse;
    }

    /**
     * @return mixed
     */
    public function getArgument()
    {
        return $this->argument;
    }

    /**
     * @param mixed $argument
     */
    public function setArgument($argument)
    {
        $this->argument = $argument;
    }

    /**
     * @return mixed
     */
    public function getDirectorNotes()
    {
        return $this->directorNotes;
    }

    /**
     * @param mixed $directorNotes
     */
    public function setDirectorNotes($directorNotes)
    {
        $this->directorNotes = $directorNotes;
    }

    /**
     * @return mixed
     */
    public function getMovieLength()
    {
        return $this->movieLength;
    }

    /**
     * @param mixed $movieLength
     */
    public function setMovieLength($movieLength)
    {
        $this->movieLength = $movieLength;
    }

    /**
     * @return mixed
     */
    public function getEstimatedValue()
    {
        return $this->estimatedValue;
    }

    /**
     * @param mixed $estimatedValue
     */
    public function setEstimatedValue($estimatedValue)
    {
        $this->estimatedValue = $estimatedValue;
    }

    /**
     * @return mixed
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param mixed $locations
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;
    }

    /**
     * @return mixed
     */
    public function getValueCapturedResources()
    {
        return $this->valueCapturedResources;
    }

    /**
     * @param mixed $valueCapturedResources
     */
    public function setValueCapturedResources($valueCapturedResources)
    {
        $this->valueCapturedResources = $valueCapturedResources;
    }

    /**
     * @return mixed
     */
    public function getValueCapturedServices()
    {
        return $this->valueCapturedServices;
    }

    /**
     * @param mixed $valueCapturedServices
     */
    public function setValueCapturedServices($valueCapturedServices)
    {
        $this->valueCapturedServices = $valueCapturedServices;
    }

    /**
     * @return mixed
     */
    public function getEstimatedTimeFilming()
    {
        return $this->estimatedTimeFilming;
    }

    /**
     * @param mixed $estimatedTimeFilming
     */
    public function setEstimatedTimeFilming($estimatedTimeFilming)
    {
        $this->estimatedTimeFilming = $estimatedTimeFilming;
    }

    /**
     * @return mixed
     */
    public function getStateProduction()
    {
        return $this->stateProduction;
    }

    /**
     * @param mixed $stateProduction
     */
    public function setStateProduction($stateProduction)
    {
        $this->stateProduction = $stateProduction;
    }

    /**
     * @return mixed
     */
    public function getPeoples()
    {
        return $this->peoples;
    }

    /**
     * @param mixed $peoples
     */
    public function setPeoples($peoples)
    {
        $this->peoples = $peoples;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
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
    public function getRelatedPartners()
    {
        return $this->relatedPartners;
    }

    /**
     * @param mixed $relatedPartners
     */
    public function setRelatedPartners($relatedPartners)
    {
        $this->relatedPartners = $relatedPartners;
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
    public function getInstituition()
    {
        return $this->instituition;
    }

    /**
     * @param mixed $instituition
     */
    public function setInstituition($instituition)
    {
        $this->instituition = $instituition;
    }

    /**
     * @return mixed
     */
    public function getShortSinopseEnglish()
    {
        return $this->shortSinopseEnglish;
    }

    /**
     * @param mixed $shortSinopseEnglish
     */
    public function setShortSinopseEnglish($shortSinopseEnglish)
    {
        $this->shortSinopseEnglish = $shortSinopseEnglish;
    }

    /**
     * @return mixed
     */
    public function getProducerNotes()
    {
        return $this->producerNotes;
    }

    /**
     * @param mixed $producerNotes
     */
    public function setProducerNotes($producerNotes)
    {
        $this->producerNotes = $producerNotes;
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

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getOption($option)
    {
        $opts = [];
        foreach ($this->options as $op) {
            if($op->getName() == $option) {
                $opts[] = $op;
            }
        }

        if(empty($opts)) {
            return false;
        }

        if(count($opts) == 1) {
            return $opts[0];
        }

        return $opts;
    }

    /**
     * @return mixed
     */
    public function getYearOfParticipation()
    {
        return $this->yearOfParticipation;
    }

    /**
     * @param mixed $yearOfParticipation
     */
    public function setYearOfParticipation($yearOfParticipation)
    {
        $this->yearOfParticipation = $yearOfParticipation;
    }

    /**
     * @return mixed
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param mixed $script
     */
    public function setScript($script): void
    {
        $this->script = $script;
    }

    /**
     * @return mixed
     */
    public function getMovieLink()
    {
        return $this->movieLink;
    }

    /**
     * @param mixed $movieLink
     */
    public function setMovieLink($movieLink): void
    {
        $this->movieLink = $movieLink;
    }

    /**
     * @return mixed
     */
    public function getMoviePass()
    {
        return $this->moviePass;
    }

    /**
     * @param mixed $moviePass
     */
    public function setMoviePass($moviePass): void
    {
        $this->moviePass = $moviePass;
    }

    /**
     * @return mixed
     */
    public function getParticipatedOtherFestivals()
    {
        return $this->participatedOtherFestivals;
    }

    /**
     * @param mixed $participatedOtherFestivals
     */
    public function setParticipatedOtherFestivals($participatedOtherFestivals): void
    {
        $this->participatedOtherFestivals = $participatedOtherFestivals;
    }



}