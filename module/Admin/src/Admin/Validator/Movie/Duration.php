<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 02/10/2017
 * Time: 15:16
 */

namespace Admin\Validator\Movie;

use Doctrine\ORM\QueryBuilder;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class Duration extends AbstractValidator
{
    /**
     * Error constants
     */
    const ERROR_IS_NOT_MARCH = 'isNotMatch';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_IS_NOT_MARCH => "Não é permitido filmes médias",
    );

    protected $min;

    protected $max;

    protected $inclusive = false;

    public function __construct($options=null)
    {
        //In seconds
        if(!empty($options['min'])) {
            $this->min = (int) $options['min'];
        }

        //In seconds
        if(!empty($options['max'])) {
            $this->max = (int) $options['max'];
        }

        if(!empty($options['inclusive'])) {
            $this->inclusive = (bool) $options['inclusive'];
        }

        parent::__construct($options);
    }

    public function isValid($value)
    {
        $time = \DateTime::createFromFormat('H:i:s', $value);

        $hours = (int) $time->format('H');
        $minutes = (int) $time->format('i');
        $seconds = (int) $time->format('s');

        $totalSeconds = (($hours*60)*60) + ($minutes*60) + $seconds;

        if($this->inclusive) {
            if(!($totalSeconds >= $this->min && $totalSeconds <= $this->max)) {
                $this->error(self::ERROR_IS_NOT_MARCH, $value);
                return false;
            }
        } else {
            if(!($totalSeconds > $this->min && $totalSeconds < $this->max)) {
                $this->error(self::ERROR_IS_NOT_MARCH, $value);
                return false;
            }
        }

        return true;
    }
}