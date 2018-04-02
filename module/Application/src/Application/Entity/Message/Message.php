<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 13/12/2017
 * Time: 15:32
 */

namespace Application\Entity\Message;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="Application\Repository\Message\Message")
 */
class Message extends AbstractEntity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="status", type="string") */
    private $status;

    /** @ORM\Column(name="not_delete", type="string") */
    private $notDelete;

    /** @ORM\Column(name="body", type="text") */
    private $body;

    /** @ORM\Column(name="subject", type="string") */
    private $subject;

    /** @ORM\Column(name="type", type="string") */
    private $type;

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
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
    public function getNotDelete()
    {
        return $this->notDelete;
    }

    /**
     * @param mixed $notDelete
     */
    public function setNotDelete($notDelete)
    {
        $this->notDelete = $notDelete;
    }
}