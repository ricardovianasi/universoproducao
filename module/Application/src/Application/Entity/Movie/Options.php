<?php
namespace Application\Entity\Movie;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="movie_option")
 * @ORM\Entity(repositoryClass="Application\Repository\Movie\Options")
 */
class Options extends AbstractEntity
{
    /** Opção dasabilitada */
    const STATUS_DISABLED = 0;

    /** Opção habilitada para qualquer usuario */
    const STATUS_ENABLED = 1;

    /** Opção habilitada somente apenas para admin */
    const STATUS_ADMIN_ENABLED = 2;

	/**
	 * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

    /** @ORM\Column(name="name", type="string") */
    private $name;

    /** @ORM\Column(name="`type`", type="string") */
    private $type;

    /** @ORM\Column(name="`status`", type="integer") */
    private $status = self::STATUS_ENABLED;

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

    public function getTypeName()
    {
        return OptionsType::get($this->getType());
    }

    public function _toArray()
    {
        return $this->toArray();
    }
}