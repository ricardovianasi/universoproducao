<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 19/02/2018
 * Time: 15:21
 */

namespace Application\Entity\Project;

use Doctrine\Common\Collections\ArrayCollection;
use Util\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="project_options")
 * @ORM\Entity
 */
class Options extends AbstractEntity
{
    CONST PHASE = 'phase';
    const GENRE = 'genre';
    const FORMAT = 'format';
    const DISPLAY_FORMAT = 'display_format';
    const WRITTEN_SCRIPT = 'written_script';
    const FIRST_OR_SECOND_PROJECT = 'first_or_second_project';
    const CATEGORY = 'category';

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(type="string", nullable=true) */
    private $name;

    /** @ORM\Column(type="string", nullable=true) */
    private $status;

    /** @ORM\Column(name="title", type="string", nullable=true) */
    private $label;

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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
}