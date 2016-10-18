<?php
namespace Application\Entity\Site;

use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="languages")
 * @ORM\Entity
 */
class Language extends AbstractEntity
{
	/**
	 * @ORM\Id @ORM\Column(name="id", type="string", nullable=false)
	 */
	private $id;

	/** @ORM\Column(name="`name`", type="string", nullable=false) */
	private $name;

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
}