<?php
namespace Application\Entity\Movie;

use Application\Entity\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="Application\Repository\Movie\Movie")
 */
class Movie extends AbstractEntity
{
    const CATEGORY_LONGA = 'longa';
    const CATEGORY_MEDIA = 'media';
    const CATEGORY_CURTA = 'curta';

	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\User\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /** @ORM\Column(name="title", type="string", nullable=true) */
    private $title;

    /** @ORM\Column(name="title_english", type="string", nullable=true) */
    private $titleEnglish;

    /** @ORM\Column(name="end_date_year", type="integer", nullable=true) */
    private $endDateYear;

    /** @ORM\Column(name="end_date_month", type="integer", nullable=true) */
    private $endDateMonth;

    /** @ORM\Column(name="production_coutry", type="string", nullable=true) */
    private $productionCoutry = 'Brasil';

    /** @ORM\Column(name="production_state", type="string", nullable=true) */
    private $productionState;

    /** @ORM\Column(name="duration", type="time", nullable=true) */
    private $duration;

    /** @ORM\Column(name="cpb", type="string", nullable=true) */
    private $cpb;

    /** @ORM\Column(name="has_cpb", type="boolean", nullable=true) */
    private $hasCpb;

    /** @ORM\Column(name="has_official_classification", type="boolean", nullable=true) */
    private $hasOfficialClassification;

    /** @ORM\Column(name="direction", type="string", nullable=true) */
    private $direction;

    /** @ORM\Column(name="script", type="string", nullable=true) */
    private $script;

    /** @ORM\Column(name="production_company", type="string", nullable=true) */
    private $productionCompany;

    /** @ORM\Column(name="direction_production", type="string", nullable=true) */
    private $directionProduction;

    /** @ORM\Column(name="photography", type="string", nullable=true) */
    private $photography;

    /** @ORM\Column(name="direction_art", type="string", nullable=true) */
    private $directionArt;

    /** @ORM\Column(name="editing_assembly", type="string", nullable=true) */
    private $editingAssembly;

    /** @ORM\Column(name="soundtrack", type="string", nullable=true) */
    private $soundtrack;

    /** @ORM\Column(name="direct_sound", type="string", nullable=true) */
    private $directSound;

    /** @ORM\Column(name="scenography", type="string", nullable=true) */
    private $scenography;

    /** @ORM\Column(name="costume", type="string", nullable=true) */
    private $costume;

    /** @ORM\Column(name="cast", type="string", nullable=true) */
    private $cast;

    /** @ORM\Column(name="synopsis", type="string", nullable=true) */
    private $synopsis;

    /** @ORM\Column(name="conversations_languages", type="string", nullable=true) */
    private $conversationsLanguages;

    /** @ORM\Column(name="subtitles_languages", type="string", nullable=true) */
    private $subtitlesLanguages;

    /** @ORM\Column(name="conversations_list_languages", type="string", nullable=true) */
    private $conversationsListLanguages;

    /** @ORM\Column(name="filmography_director", type="string", nullable=true) */
    private $filmographyDirector;

    /** @ORM\Column(name="other_festivals", type="string", nullable=true) */
    private $otherFestivals;

    /** @ORM\Column(name="movie_link", type="string", nullable=true) */
    private $movieLink;

    /** @ORM\Column(name="movie_divulgation", type="string", nullable=true) */
    private $movieDivulgation;

    /** @ORM\Column(name="movie_password", type="string", nullable=true) */
    private $moviePassword;

    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private $updatedAt;

    /** @ORM\OneToMany(targetEntity="Media", mappedBy="movie", cascade={"ALL"})  */
    private $medias;

    /** @ORM\OneToMany(targetEntity="MovieEvent", mappedBy="movie", cascade={"ALL"}) */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Registration\Registration")
     * @ORM\JoinColumn(name="registration_id", referencedColumnName="id")
     */
    private $registration;

    /** @ORM\Column(name="co_production", type="string", nullable=true) */
    private $coProduction;

    /** @ORM\Column(name="executive_production", type="string", nullable=true) */
    private $executiveProduction;

    /** @ORM\Column(name="mixing", type="string", nullable=true) */
    private $mixing;

    /** @ORM\Column(name="sound_editing", type="string", nullable=true) */
    private $soundEditing;

    /** @ORM\Column(name="content_scenes", type="string", nullable=true) */
    private $contentScenes;

    /** @ORM\Column(name="has_participated_other_festivals", type="boolean", nullable=true) */
    private $hasParticipatedOtherFestivals;

    /** @ORM\Column(name="has_conversations_languages", type="boolean", nullable=true) */
    private $hasConversationsLanguages;

    /** @ORM\Column(name="has_subtitles_languages", type="boolean", nullable=true) */
    private $hasSubtitlesLanguages;

    /** @ORM\Column(name="has_conversations_list_languages", type="boolean", nullable=true) */
    private $hasConversationsListLanguages;

    /**
     * @ORM\ManyToMany(targetEntity="Options")
     * @ORM\JoinTable(name="movie_has_options",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="movie_option_id", referencedColumnName="id")}
     * )
     */
    private $options;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->options = new ArrayCollection();
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
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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
    public function getTitleEnglish()
    {
        return $this->titleEnglish;
    }

    /**
     * @param mixed $titleEnglish
     */
    public function setTitleEnglish($titleEnglish)
    {
        $this->titleEnglish = $titleEnglish;
    }

    /**
     * @return mixed
     */
    public function getEndDateYear()
    {
        return $this->endDateYear;
    }

    /**
     * @param mixed $endDateYear
     */
    public function setEndDateYear($endDateYear)
    {
        $this->endDateYear = $endDateYear;
    }

    /**
     * @return mixed
     */
    public function getEndDateMonth()
    {
        return $this->endDateMonth;
    }

    /**
     * @param mixed $endDateMonth
     */
    public function setEndDateMonth($endDateMonth)
    {
        $this->endDateMonth = $endDateMonth;
    }

    /**
     * @return mixed
     */
    public function getProductionCoutry()
    {
        return $this->productionCoutry;
    }

    /**
     * @param mixed $productionCoutry
     */
    public function setProductionCoutry($productionCoutry)
    {
        $this->productionCoutry = $productionCoutry;
    }

    /**
     * @return mixed
     */
    public function getProductionState()
    {
        return $this->productionState;
    }

    /**
     * @param mixed $productionState
     */
    public function setProductionState($productionState)
    {
        $this->productionState = $productionState;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        if(is_string($duration)) {
            $this->duration = \DateTime::createFromFormat('H:i:s', $duration);
        } elseif($duration instanceof \DateTime) {
            $this->duration = $duration;
        }
    }

    /**
     * @return mixed
     */
    public function getCpb()
    {
        return $this->cpb;
    }

    /**
     * @param mixed $cpb
     */
    public function setCpb($cpb)
    {
        $this->cpb = $cpb;
    }

    /**
     * @return mixed
     */
    public function getHasOfficialClassification()
    {
        return $this->hasOfficialClassification;
    }

    /**
     * @param mixed $hasOfficialClassification
     */
    public function setHasOfficialClassification($hasOfficialClassification)
    {
        $this->hasOfficialClassification = $hasOfficialClassification;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
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
    public function setScript($script)
    {
        $this->script = $script;
    }

    /**
     * @return mixed
     */
    public function getProductionCompany()
    {
        return $this->productionCompany;
    }

    /**
     * @param mixed $productionCompany
     */
    public function setProductionCompany($productionCompany)
    {
        $this->productionCompany = $productionCompany;
    }

    /**
     * @return mixed
     */
    public function getDirectionProduction()
    {
        return $this->directionProduction;
    }

    /**
     * @param mixed $directionProduction
     */
    public function setDirectionProduction($directionProduction)
    {
        $this->directionProduction = $directionProduction;
    }

    /**
     * @return mixed
     */
    public function getPhotography()
    {
        return $this->photography;
    }

    /**
     * @param mixed $photography
     */
    public function setPhotography($photography)
    {
        $this->photography = $photography;
    }

    /**
     * @return mixed
     */
    public function getDirectionArt()
    {
        return $this->directionArt;
    }

    /**
     * @param mixed $directionArt
     */
    public function setDirectionArt($directionArt)
    {
        $this->directionArt = $directionArt;
    }

    /**
     * @return mixed
     */
    public function getEditingAssembly()
    {
        return $this->editingAssembly;
    }

    /**
     * @param mixed $editingAssembly
     */
    public function setEditingAssembly($editingAssembly)
    {
        $this->editingAssembly = $editingAssembly;
    }

    /**
     * @return mixed
     */
    public function getSoundtrack()
    {
        return $this->soundtrack;
    }

    /**
     * @param mixed $soundtrack
     */
    public function setSoundtrack($soundtrack)
    {
        $this->soundtrack = $soundtrack;
    }

    /**
     * @return mixed
     */
    public function getDirectSound()
    {
        return $this->directSound;
    }

    /**
     * @param mixed $directSound
     */
    public function setDirectSound($directSound)
    {
        $this->directSound = $directSound;
    }

    /**
     * @return mixed
     */
    public function getScenography()
    {
        return $this->scenography;
    }

    /**
     * @param mixed $scenography
     */
    public function setScenography($scenography)
    {
        $this->scenography = $scenography;
    }

    /**
     * @return mixed
     */
    public function getCostume()
    {
        return $this->costume;
    }

    /**
     * @param mixed $costume
     */
    public function setCostume($costume)
    {
        $this->costume = $costume;
    }

    /**
     * @return mixed
     */
    public function getCast()
    {
        return $this->cast;
    }

    /**
     * @param mixed $cast
     */
    public function setCast($cast)
    {
        $this->cast = $cast;
    }

    /**
     * @return mixed
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * @param mixed $synopsis
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;
    }

    /**
     * @return mixed
     */
    public function getConversationsLanguages()
    {
        return $this->conversationsLanguages;
    }

    /**
     * @param mixed $conversationsLanguages
     */
    public function setConversationsLanguages($conversationsLanguages)
    {
        $this->conversationsLanguages = $conversationsLanguages;
    }

    /**
     * @return mixed
     */
    public function getSubtitlesLanguages()
    {
        return $this->subtitlesLanguages;
    }

    /**
     * @param mixed $subtitlesLanguages
     */
    public function setSubtitlesLanguages($subtitlesLanguages)
    {
        $this->subtitlesLanguages = $subtitlesLanguages;
    }

    /**
     * @return mixed
     */
    public function getConversationsListLanguages()
    {
        return $this->conversationsListLanguages;
    }

    /**
     * @param mixed $conversationsListLanguages
     */
    public function setConversationsListLanguages($conversationsListLanguages)
    {
        $this->conversationsListLanguages = $conversationsListLanguages;
    }

    /**
     * @return mixed
     */
    public function getFilmographyDirector()
    {
        return $this->filmographyDirector;
    }

    /**
     * @param mixed $filmographyDirector
     */
    public function setFilmographyDirector($filmographyDirector)
    {
        $this->filmographyDirector = $filmographyDirector;
    }

    /**
     * @return mixed
     */
    public function getOtherFestivals()
    {
        return $this->otherFestivals;
    }

    /**
     * @param mixed $otherFestivals
     */
    public function setOtherFestivals($otherFestivals)
    {
        $this->otherFestivals = $otherFestivals;
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
    public function setMovieLink($movieLink)
    {
        $this->movieLink = $movieLink;
    }

    /**
     * @return mixed
     */
    public function getMovieDivulgation()
    {
        return $this->movieDivulgation;
    }

    /**
     * @param mixed $movieDivulgation
     */
    public function setMovieDivulgation($movieDivulgation)
    {
        $this->movieDivulgation = $movieDivulgation;
    }

    /**
     * @return mixed
     */
    public function getMoviePassword()
    {
        return $this->moviePassword;
    }

    /**
     * @param mixed $moviePassword
     */
    public function setMoviePassword($moviePassword)
    {
        $this->moviePassword = $moviePassword;
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

    public function getMediaById($mediaId)
    {
        foreach ($this->medias as $key=>$m) {
            if($m->getId() == $mediaId) {
                return $m;
            }
        }

        return false;
    }

    /**
     * @return ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param mixed $medias
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param mixed $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @return Registration|null
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
    public function getCoProduction()
    {
        return $this->coProduction;
    }

    /**
     * @param mixed $coProduction
     */
    public function setCoProduction($coProduction)
    {
        $this->coProduction = $coProduction;
    }

    /**
     * @return mixed
     */
    public function getExecutiveProduction()
    {
        return $this->executiveProduction;
    }

    /**
     * @param mixed $executiveProduction
     */
    public function setExecutiveProduction($executiveProduction)
    {
        $this->executiveProduction = $executiveProduction;
    }

    /**
     * @return mixed
     */
    public function getMixing()
    {
        return $this->mixing;
    }

    /**
     * @param mixed $mixing
     */
    public function setMixing($mixing)
    {
        $this->mixing = $mixing;
    }

    /**
     * @return mixed
     */
    public function getSoundEditing()
    {
        return $this->soundEditing;
    }

    /**
     * @param mixed $soundEditing
     */
    public function setSoundEditing($soundEditing)
    {
        $this->soundEditing = $soundEditing;
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
    public function getHasCpb()
    {
        return $this->hasCpb;
    }

    /**
     * @param mixed $hasCpb
     */
    public function setHasCpb($hasCpb)
    {
        $this->hasCpb = $hasCpb;
    }

    /**
     * @return mixed
     */
    public function getContentScenes()
    {
        return $this->contentScenes;
    }

    /**
     * @param mixed $contentScenes
     */
    public function setContentScenes($contentScenes)
    {
        $this->contentScenes = $contentScenes;
    }

    /**
     * @return mixed
     */
    public function getHasParticipatedOtherFestivals()
    {
        return $this->hasParticipatedOtherFestivals;
    }

    /**
     * @param mixed $hasParticipatedOtherFestivals
     */
    public function setHasParticipatedOtherFestivals($hasParticipatedOtherFestivals)
    {
        $this->hasParticipatedOtherFestivals = $hasParticipatedOtherFestivals;
    }

    public function getOption($option)
    {
        $opts = [];
        foreach ($this->options as $op) {
            if($op->getType() == $option) {
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
    public function getHasConversationsLanguages()
    {
        return $this->hasConversationsLanguages;
    }

    /**
     * @param mixed $hasConversationsLanguages
     */
    public function setHasConversationsLanguages($hasConversationsLanguages)
    {
        $this->hasConversationsLanguages = $hasConversationsLanguages;
    }

    /**
     * @return mixed
     */
    public function getHasSubtitlesLanguages()
    {
        return $this->hasSubtitlesLanguages;
    }

    /**
     * @param mixed $hasSubtitlesLanguages
     */
    public function setHasSubtitlesLanguages($hasSubtitlesLanguages)
    {
        $this->hasSubtitlesLanguages = $hasSubtitlesLanguages;
    }

    /**
     * @return mixed
     */
    public function getHasConversationsListLanguages()
    {
        return $this->hasConversationsListLanguages;
    }

    /**
     * @param mixed $hasConversationsListLanguages
     */
    public function setHasConversationsListLanguages($hasConversationsListLanguages)
    {
        $this->hasConversationsListLanguages = $hasConversationsListLanguages;
    }

    public function getCategory()
    {
        if (!$this->duration) {
            return;
        }

        if ($this->getRegistration()) {
            $durationCurtaTo = $this->getRegistration()->getOption(\Application\Entity\Registration\Options::MOVIE_DURATION_CURTA_TO);

            $durationMediaFrom = $this->getRegistration()->getOption(\Application\Entity\Registration\Options::MOVIE_DURATION_MEDIA_FROM);
            $durationMediaTo = $this->getRegistration()->getOption(\Application\Entity\Registration\Options::MOVIE_DURATION_MEDIA_TO);

            $durationLongaFrom = $this->getRegistration()->getOption(\Application\Entity\Registration\Options::MOVIE_DURATION_LONGA_TO);
        }

        if ($durationCurtaTo) {
            $durationCurtaTo = \DateTime::createFromFormat('H:i:s', $durationCurtaTo->getValue());
        } else {
            $durationCurtaTo = \DateTime::createFromFormat('H:i:s', '00:30:00');
        }

        if ($durationMediaFrom) {
            $durationMediaFrom = \DateTime::createFromFormat('H:i:s', $durationMediaFrom->getValue());
        } else {
            $durationMediaFrom = \DateTime::createFromFormat('H:i:s', '00:30:01');
        }

        if ($durationMediaTo) {
            $durationMediaTo = \DateTime::createFromFormat('H:i:s', $durationMediaTo->getValue());
        } else {
            $durationMediaTo = \DateTime::createFromFormat('H:i:s', '00:59:59');
        }

        if ($durationLongaFrom) {
            $durationLongaFrom = \DateTime::createFromFormat('H:i:s', $durationLongaFrom->getValue());
        } else {
            $durationLongaFrom = \DateTime::createFromFormat('H:i:s', '01:00:00');
        }

        $durationInSeconds = $this->timeToSeconds($this->duration);
        $durationCurtaToSeconds = $this->timeToSeconds($durationCurtaTo);
        $durationMediaFromSeconds = $this->timeToSeconds($durationMediaFrom);
        $durationMediaToSeconds = $this->timeToSeconds($durationMediaTo);
        $durationLongaFromSeconds = $this->timeToSeconds($durationLongaFrom);

        if ($durationInSeconds <= $durationCurtaToSeconds) {
            return Category::CURTA;
        } elseif ($durationInSeconds > $durationMediaFromSeconds && $durationInSeconds < $durationMediaToSeconds) {
            return Category::MEDIA;
        } elseif ($durationInSeconds >= $durationLongaFromSeconds) {
            return Category::LONGA;
        }
    }
}