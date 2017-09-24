<?php
namespace Util\Entity;

use Zend\Hydrator\ClassMethods;

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
}