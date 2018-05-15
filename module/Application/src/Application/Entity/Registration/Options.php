<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 23/09/2017
 * Time: 12:43
 */

namespace Application\Entity\Registration;

use Doctrine\ORM\Mapping as ORM;
use Util\Entity\AbstractEntity;

/**
 * @ORM\Table(name="registration_options")
 * @ORM\Entity
 */
class Options extends AbstractEntity
{
    //Movies Options
    const MOVIE_TYPE                                = 'movie_type';
    const MOVIE_ALLOW_INTERNATIONAL_REGISTRATION    = 'movie_allow_international_registration';
    const MOVIE_ALLOW_FINISHED_FROM                 = 'movie_allow_finished_from';
    const MOVIE_ALLOW_FINISHED_TO                   = 'movie_allow_finished_to';
    const MOVIE_ALLOW_EDIT_REGISTRATION_TO          = 'movie_allow_edit_registration_to';
    const MOVIE_DURATION_OBS                        = 'movie_duration_obs';
    const MOVIE_DURATION_CURTA_FROM                 = 'movie_duration_curta_from';
    const MOVIE_DURATION_CURTA_TO                   = 'movie_duration_curta_to';
    const MOVIE_DURATION_MEDIA_FROM                 = 'movie_duration_media_from';
    const MOVIE_DURATION_MEDIA_TO                   = 'movie_duration_media_to';
    const MOVIE_DURATION_LONGA_FROM                 = 'movie_duration_longa_from';
    const MOVIE_DURATION_LONGA_TO                   = 'movie_duration_longa_to';

    //Workshop Options
    const WORKSHOP_FORM                             = 'workshop_form';
    CONST WORKSHOP_PONTUATION                       = 'workshop_pontuation';

    const SEMINAR_CATEGORY                          = 'seminar_category';
    const SEMINAR_AVALIABLE                         = 'seminar_avaliable';


    /**
     * @ORM\Id @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="name", type="string", nullable=true) */
    private $name;

    /** @ORM\Column(name="value", type="string", nullable=true) */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Registration", inversedBy="options")
     * @ORM\JoinColumn(name="registration_id", referencedColumnName="id")
     */
    private $registration;

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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
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

    public function __toString()
    {
        return (string) $this->getValue();
    }
}