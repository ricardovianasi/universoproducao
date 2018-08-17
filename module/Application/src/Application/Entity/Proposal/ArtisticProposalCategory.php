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
 * @ORM\Table(name="artistic_proposal_categories")
 * @ORM\Entity
 */
class ArtisticProposalCategory extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="name", type="string", nullable=true) */
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