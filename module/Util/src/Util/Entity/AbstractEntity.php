<?php
namespace Util\Entity;

use Zend\Hydrator\ClassMethods;
use Zend\Validator\Date;

abstract class AbstractEntity
{
	protected $defaultInputFilter = [
		['name' => 'StripTags'],
		['name' => 'StringTrim'],
	];

	public function setData($data) {

	    $data = (array) $data;

		$hydrator = new ClassMethods();
		$hydrator->hydrate($data, $this);
	}

	/**
	 * Transforma o objeto em um array
	 * @return array
	 */
	public function toArray() {
		$hydrator = new ClassMethods();
		return $hydrator->extract($this);
	}

	/**
	 * Transforma uma string com uma data em um objeto do tipo \DateTime
	 * 
	 * @param string $date
	 * @param $property
	 * @return \DateTime
	 */
	public function parseData($date, &$property=null, $nowIfEmpty=false) {
		if($date instanceof \DateTime) {
			$property = $date;
		} elseif (preg_match ('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $date)) {
			$property = \DateTime::createFromFormat ('d/m/Y', $date);
		} elseif (preg_match ('/^\d{1,2}\/\d{1,2}\/\d{4}\ \d{1,2}\:\d{1,2}$/', $date)) {
            $property = \DateTime::createFromFormat ('d/m/Y H:i', $date);
        } elseif (preg_match ( '/^\d{4}\-\d{1,2}\-\d{1,2}$/', $date )) {
			$property = new \DateTime($date);
		} elseif (preg_match ( '/^\d{4}\-\d{1,2}\-\d{1,2}\ \d{1,2}\:\d{1,2}$/', $date )) {
            $property = new \DateTime($date);
        } elseif($nowIfEmpty && empty($property)) {
			$property = new \DateTime();
		} else {
			return null;
		}

		return $property;
	}

	public function getDefaultInputFilters()
	{
		return $this->defaultInputFilter;
	}

	public function timeToSeconds($time)
    {
        if(!$time) {
            throw new \InvalidArgumentException();
        }

        $dateValidator = new Date([
            'format' => 'H:i:s'
        ]);

        if(!$dateValidator->isValid($time)) {
            throw new \Exception('Formato não válido');
        }

	    if(is_string($time)) {
	        $time = \DateTime::createFromFormat('H:i:s', $time);
        }

        if(!($time instanceof \DateTime)) {
            throw new \Exception('Formato não válido');
        }

        $h = (int) $time->format('H');
        $m = (int) $time->format('i');
        $s = (int) $time->format('s');

        return (($h*60)*60)+($m*60)+$s;
    }
}