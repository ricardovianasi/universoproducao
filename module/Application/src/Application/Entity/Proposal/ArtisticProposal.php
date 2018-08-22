<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 16/08/2018
 * Time: 15:04
 */
namespace Application\Entity\Proposal;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="artistic_proposal")
 * @ORM\Entity(repositoryClass="Application\Repository\Proposal\ArtisticProposal")
 */
class ArtisticProposal extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="artist_name", type="string", nullable=true) */
    private $artistName;

    /** @ORM\Column(name="show_name", type="string", nullable=true) */
    private $showName;

    /** @ORM\Column(type="string", nullable=true) */
    private $genre;

    /** @ORM\Column(type="string", nullable=true) */
    private $duration;

    /** @ORM\Column(name="target_public", type="string", nullable=true) */
    private $targetPublic;

    /** @ORM\Column(name="suggested_cache", type="string", nullable=true) */
    private $suggestedCache;

    /** @ORM\Column(name="staff_quantity", type="string", nullable=true) */
    private $staffQuantity;

    /** @ORM\Column(name="incentive_law", type="string", nullable=true) */
    private $incentiveLaw;

    /** @ORM\Column(type="text", nullable=true) */
    private $links;

    /** @ORM\Column(type="text", nullable=true) */
    private $description;

    /** @ORM\Column(type="text", nullable=true) */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="ArtisticProposalCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

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
    public function getArtistName()
    {
        return $this->artistName;
    }

    /**
     * @param mixed $artistName
     */
    public function setArtistName($artistName)
    {
        $this->artistName = $artistName;
    }

    /**
     * @return mixed
     */
    public function getShowName()
    {
        return $this->showName;
    }

    /**
     * @param mixed $showName
     */
    public function setShowName($showName)
    {
        $this->showName = $showName;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
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
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getTargetPublic()
    {
        return $this->targetPublic;
    }

    /**
     * @param mixed $targetPublic
     */
    public function setTargetPublic($targetPublic)
    {
        $this->targetPublic = $targetPublic;
    }

    /**
     * @return mixed
     */
    public function getSuggestedCache()
    {
        return $this->suggestedCache;
    }

    /**
     * @param mixed $suggestedCache
     */
    public function setSuggestedCache($suggestedCache)
    {
        $this->suggestedCache = $suggestedCache;
    }

    /**
     * @return mixed
     */
    public function getStaffQuantity()
    {
        return $this->staffQuantity;
    }

    /**
     * @param mixed $staffQuantity
     */
    public function setStaffQuantity($staffQuantity)
    {
        $this->staffQuantity = $staffQuantity;
    }

    /**
     * @return mixed
     */
    public function getIncentiveLaw()
    {
        return $this->incentiveLaw;
    }

    /**
     * @param mixed $incentiveLaw
     */
    public function setIncentiveLaw($incentiveLaw)
    {
        $this->incentiveLaw = $incentiveLaw;
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